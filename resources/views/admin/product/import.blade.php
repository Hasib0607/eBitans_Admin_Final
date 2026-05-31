@extends('admin.layouts.main')
@push('styles')
    <style>
        div#progressBar {
            color: green;
            font-size: 15px;
            height: 25px;
        }

        .progress {
            height: 55px !important;
        }

        .btn-close {
            background: #b1b1b1;
            color: red;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 20px;
            padding: 5px;
            font-weight: 600;
        }

        .btn-close:hover {
            color: red !important;
        }

        .swal-like-alert {
            padding: 20px;
            border-radius: 8px;
            background-color: #e6ffed;
            border: 1px solid #b3f3c0;
            color: #2f855a;
            font-weight: 500;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            animation: fadeIn 0.5s ease-in-out;
        }

        .swal-like-alert i {
            font-size: 24px;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>

@endpush
@section('content')
    <main class="main-content position-relative border-radius-lg">
        <div class="container">
            <h2>🧾 Bulk Product Import</h2>

            <!-- File Upload Form -->
            <form id="importForm" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="excel_file" class="form-label">Select Excel File</label>
                    <input class="form-control" type="file" id="excel_file" name="file" accept=".xls,.xlsx">
                </div>
                <button type="button" class="btn btn-primary" id="previewBtn">Preview</button>
            </form>


            <!-- Modal for Preview/Import Result -->
            <div class="modal fade" id="previewModal" tabindex="-1" aria-labelledby="importModalLabel"
                 aria-hidden="true"
                 data-bs-backdrop="static" data-bs-keyboard="false">

                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="previewModalLabel">📊 Preview Excel Data</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">x
                            </button>
                        </div>
                        <div class="modal-body">
                            <!-- Import Progress -->
                            <div class="d-none" id="importProgress" style="margin-bottom: 10px;">
                                <div class="progress" style="background: #e6f4ff;padding: 15px; color: green;">
                                    <div class="progress-bar progress-bar-striped progress-bar-animated"
                                         role="progressbar"
                                         style="width: 0%" id="progressBar">0%
                                    </div>
                                </div>
                            </div>
                            <div id="showMessage"></div>
                            <div class="table-responsive" id="importTableContainer">
                                <table class="table table-bordered" id="previewTable">
                                    <thead>
                                    <tr>
                                        <th>SL</th>
                                        <th>Type</th>
                                        <th>Product Name</th>
                                        <th>Product Description</th>
                                        <th>Product Images</th>
                                        <th>Category</th>
                                        <th>Sub-Category</th>
                                        <th>SKU</th>
                                        <th>Product Price</th>
                                        <th>Quantity</th>
                                        <th>Variant Type</th>
                                        <th>Variant Value</th>
                                        <th>Variant Quantity</th>
                                        <th>Additional Price</th>
                                        <th>Variant Image</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <!-- Rows injected by JS -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="modal-footer d-flex justify-content-between align-items-center">
                            <div id="paginationControls" class="d-flex"></div>
                            <div>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-success" id="startImport">Start Import</button>
                                <button type="button" class="btn btn-danger d-none" id="retryAll">Retry All Failed
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@push('scripts')
    <script>
        let excelData = [];
        const rowsPerPage = 10;
        let currentPage = 1;

        function renderTablePage(page) {
            const start = (page - 1) * rowsPerPage;
            const end = start + rowsPerPage;
            const pageData = excelData.slice(start + 1, end + 1); // +1 because row[0] is header

            const tbody = document.querySelector('#previewTable tbody');
            tbody.innerHTML = '';

            pageData.forEach((row, index) => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${start + index + 1}</td>
                    <td contenteditable="true">${row[0] ?? ''}</td>  <!-- Type -->
                    <td contenteditable="true">${row[1] ?? ''}</td>  <!-- Name -->
                    <td contenteditable="true">${row[2] ?? ''}</td>  <!-- Description -->
                    <td contenteditable="true">${row[3] ?? ''}</td>  <!-- Product Images -->
                    <td contenteditable="true">${row[4] ?? ''}</td>  <!-- Category -->
                    <td contenteditable="true">${row[5] ?? ''}</td>  <!-- Sub Category -->
                    <td contenteditable="true">${row[6] ?? ''}</td>  <!-- SKU -->
                    <td contenteditable="true">${row[7] ?? ''}</td>  <!-- Price -->
                    <td contenteditable="true">${row[8] ?? ''}</td>  <!-- Quantity -->
                    <td contenteditable="true">${row[9] ?? ''}</td>  <!-- Variant Type -->
                    <td contenteditable="true">${row[10] ?? ''}</td> <!-- Variant Value -->
                    <td contenteditable="true">${row[11] ?? ''}</td> <!-- Variant Quantity -->
                    <td contenteditable="true">${row[12] ?? ''}</td> <!-- Additional Price -->
                    <td contenteditable="true">${row[13] ?? ''}</td> <!-- Variant Image -->
                    <td class="status">⏳ Pending</td>
                    <td><button class="btn btn-sm btn-warning retry d-none">Retry</button></td>
            `;
                tbody.appendChild(tr);
            });

            renderPaginationControls();
        }

        function renderPaginationControls() {
            const totalPages = Math.ceil((excelData.length - 1) / rowsPerPage); // skip header
            const container = document.getElementById('paginationControls');
            container.innerHTML = '';

            for (let i = 1; i <= totalPages; i++) {
                const btn = document.createElement('button');
                btn.className = `btn btn-sm ${i === currentPage ? 'btn-primary' : 'btn-outline-secondary'} me-1`;
                btn.innerText = i;
                btn.onclick = () => {
                    currentPage = i;
                    renderTablePage(currentPage);
                };
                container.appendChild(btn);
            }
        }

        // On file change
        document.getElementById('excel_file').addEventListener('change', handlePreview);

        // Still allow manual click if needed
        document.getElementById('previewBtn').addEventListener('click', handlePreview);

        function handlePreview() {
            const fileInput = document.getElementById('excel_file');
            if (!fileInput.files[0]) return Swal.fire('⚠️ Please select an Excel file.', '', 'warning');

            const formData = new FormData();
            formData.append('excel_file', fileInput.files[0]);

            axios.post("{{ route('admin.products.import.preview') }}", formData, {
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}",
                    'Content-Type': 'multipart/form-data'
                }
            })
                .then(response => {
                    excelData = response.data;

                    currentPage = 1;
                    renderTablePage(currentPage);
                    const modal = new bootstrap.Modal(document.getElementById('previewModal'));
                    modal.show();

                })
                .catch(error => {
                    console.error("Preview failed:", error);
                    Swal.fire('⚠️ Failed to load preview.', '', 'warning')
                });
        }


        let lastFailedRows = [];

        document.getElementById('startImport').addEventListener('click', async function () {
            const rows = document.querySelectorAll('#previewTable tbody tr');
            const allRows = [];

            document.getElementById('importProgress').classList.remove('d-none');
            const bar = document.getElementById('progressBar');

            rows.forEach(row => {
                allRows.push({
                    type: row.cells[1].innerText.trim(),
                    name: row.cells[2].innerText.trim(),
                    description: row.cells[3].innerText.trim(),
                    product_images: row.cells[4].innerText.trim(),
                    category: row.cells[5].innerText.trim(),
                    subcategory: row.cells[6].innerText.trim(),
                    sku: row.cells[7].innerText.trim(),
                    price: row.cells[8].innerText.trim(),
                    quantity: row.cells[9].innerText.trim(),
                    variant_type: row.cells[10].innerText.trim(),
                    variant_value: row.cells[11].innerText.trim(),
                    variant_quantity: row.cells[12].innerText.trim(),
                    additional_price: row.cells[13].innerText.trim(),
                    variant_image: row.cells[14].innerText.trim(),
                });
            });

            try {
                const response = await axios.post(
                    "{{ route('admin.products.import.process') }}",
                    {rows: allRows},
                    {
                        headers: {
                            'X-CSRF-TOKEN': "{{ csrf_token() }}",
                            'Content-Type': 'application/json'
                        }
                    }
                );

                const result = response.data;
                lastFailedRows = []; // Reset for retry later

                let newTableData = [];

                rows.forEach((row, index) => {
                    const statusCell = row.querySelector('.status');
                    const retryBtn = row.querySelector('.retry');
                    const failedRow = result.failed.find(r => r.index === index);

                    if (failedRow) {
                        statusCell.innerHTML = `❌ Failed: ${failedRow.error}`;
                        retryBtn.classList.remove('d-none');
                        lastFailedRows.push(allRows[index]);
                        newTableData.push(row);
                    } else {
                        row.remove(); // ✅ Remove successful row
                    }

                    const percent = Math.round(((index + 1) / rows.length) * 100);
                    bar.style.width = percent + '%';
                    bar.innerText = percent + '%';
                });

                if (lastFailedRows.length === 0) {
                    var importTableContainer = document.getElementById('importTableContainer');
                    if (importTableContainer && !importTableContainer.classList.contains('d-none')) {
                        importTableContainer.classList.add('d-none');
                    }

                    var startImport = document.getElementById('startImport');
                    if (startImport && !startImport.classList.contains('d-none')) {
                        startImport.classList.add('d-none');
                    }


                    var retryAll = document.getElementById('retryAll');
                    if (retryAll && !retryAll.classList.contains('d-none')) {
                        retryAll.classList.add('d-none');
                    }

                    document.getElementById('paginationControls').innerHTML = "";

                    document.getElementById('showMessage').innerHTML = `
                        <div class="swal-like-alert">
                            <i class="fa fa-check-circle"></i>
                            All products imported successfully!
                        </div>
                    `;
                    // Swal.fire('✅ All rows imported successfully!', '', 'success');
                } else {
                    var startImportEl = document.getElementById('startImport');
                    if (startImportEl && !startImportEl.classList.contains('d-none')) {
                        startImportEl.classList.add('d-none');
                    }

                    var retryAllEl = document.getElementById('retryAll');
                    if (retryAllEl && retryAllEl.classList.contains('d-none')) {
                        retryAllEl.classList.remove('d-none');
                    }

                    Swal.fire('⚠️ Some rows failed. Fix them or click Retry.', '', 'warning');
                }
            } catch (error) {
                Swal.fire('❌ Import request failed.', '', 'error');
            }
        });


        document.addEventListener('click', async function (e) {
            if (e.target.classList.contains('retry')) {
                const row = e.target.closest('tr');
                const data = {
                    type: row.cells[1].innerText.trim(),
                    name: row.cells[2].innerText.trim(),
                    description: row.cells[3].innerText.trim(),
                    product_images: row.cells[4].innerText.trim(),
                    category: row.cells[5].innerText.trim(),
                    subcategory: row.cells[6].innerText.trim(),
                    sku: row.cells[7].innerText.trim(),
                    price: row.cells[8].innerText.trim(),
                    quantity: row.cells[9].innerText.trim(),
                    variant_type: row.cells[10].innerText.trim(),
                    variant_value: row.cells[11].innerText.trim(),
                    variant_quantity: row.cells[12].innerText.trim(),
                    additional_price: row.cells[13].innerText.trim(),
                    variant_image: row.cells[14].innerText.trim(),
                };

                const statusCell = row.querySelector('.status');
                const retryBtn = row.querySelector('.retry');

                statusCell.innerHTML = '⏳ Retrying...';
                retryBtn.disabled = true;

                try {
                    const response = await axios.post(
                        "{{ route('admin.products.import.process') }}",
                        {rows: [data]},
                        {
                            headers: {
                                'X-CSRF-TOKEN': "{{ csrf_token() }}",
                                'Content-Type': 'application/json'
                            }
                        }
                    );

                    const failed = response.data.failed;

                    if (failed.length === 0) {
                        statusCell.innerHTML = '✅ Success';
                        retryBtn.classList.add('d-none');
                        row.remove();
                        Swal.fire('✅ Row imported successfully!', '', 'success');
                    } else {
                        statusCell.innerHTML = `❌ Failed: ${failed[0].error}`;
                        retryBtn.disabled = false;
                        Swal.fire('⚠️ Still failed.', failed[0].error, 'error');
                    }
                } catch (err) {
                    console.error(err);
                    Swal.fire('❌ Retry failed due to error.', '', 'error');
                }
            }
        });


        document.getElementById('retryAll').addEventListener('click', async function () {
            if (lastFailedRows.length === 0) return Swal.fire('✅ No failed rows to retry.');

            try {
                const response = await axios.post(
                    "{{ route('admin.products.import.process') }}",
                    {rows: lastFailedRows},
                    {
                        headers: {
                            'X-CSRF-TOKEN': "{{ csrf_token() }}",
                            'Content-Type': 'application/json'
                        }
                    }
                );

                const result = response.data;
                let retriedSuccess = 0;

                document.querySelectorAll('#previewTable tbody tr').forEach((row, index) => {
                    const statusCell = row.querySelector('.status');
                    const retryBtn = row.querySelector('.retry');

                    const failedRow = result.failed.find(r => r.index === index);
                    if (!failedRow) {
                        statusCell.innerHTML = '✅ Success';
                        retryBtn.classList.add('d-none');
                        row.remove();
                        retriedSuccess++;
                    } else {
                        statusCell.innerHTML = `❌ Failed: ${failedRow.error}`;
                    }
                });

                Swal.fire(`🔁 Retried: ${retriedSuccess} succeeded.`, '', 'info');
            } catch (err) {
                console.error(err);
                Swal.fire('❌ Retry all failed.', '', 'error');
            }
        });


    </script>

@endpush
