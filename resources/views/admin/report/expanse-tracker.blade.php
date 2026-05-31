@push("styles")
    <style>
        .btn.btn-sm i, .btn-group-sm > .btn i {
            font-size: 1rem;
        }

        #showCategoryListBtn {
            margin-bottom: 0px !important;
        }

        div#searchDiv {
            padding: 0 50px;
            padding-bottom: 20px;
        }
    </style>
@endpush
<!-- Expense Modal -->
<div class="modal fade" id="expenseModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="expenseModalTitle">Expense Tracker</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="expanseTopNav">
                    <div class="d-flex justify-content-between mb-3">
                        <button id="viewExpensesBtn" class="btn btn-info btn-sm">
                            <i class="fa fa-list"></i> View Expenses
                        </button>
                        <button id="addExpenseBtn" class="btn btn-primary btn-sm">
                            <i class="fa fa-plus"></i> Add Expense
                        </button>
                    </div>
                </div>

                <div id="expenseListSection">
                    <div class="row" id="searchDiv">
                        <div class="col-md-3">
                            <input type="date" name="from_date" id="from_date"
                                   value=""
                                   class="form-control">
                        </div>
                        <div class="col-md-1 mt-1">
                            <label for="to_date">To</label>
                        </div>
                        <div class="col-md-3">
                            <input type="date" name="to_date" id="to_date" value=""
                                   class="form-control">
                        </div>
                        <div class="col-md-1">
                            <button id="filterExpanse" class="btn btn-dark" style="margin-left: -12px;"><i
                                    class="fa fa-search"
                                    aria-hidden="true"></i></button>
                        </div>
                        <div class="col col-md-4">
                            <div class="input-group">
                                <input type="text" name="search" id="search" value=""
                                       class="form-control">
                                <span class="input-group-text" style="padding: 0.75rem 11px !important;">
                                                <i class="fa fa-search" aria-hidden="true"></i>
                                            </span>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped" id="expensesTable">
                            <thead>
                            <tr>
                                <th>Date</th>
                                <th>Description</th>
                                <th>Category</th>
                                <th>Amount</th>
                                <th>Notes</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody id="expensesTableBody">
                            <!-- AJAX will load data here -->
                            </tbody>
                        </table>
                    </div>
                    <div id="expensePagination" class="d-flex justify-content-center">
                        <!-- Pagination will be added here -->
                    </div>
                </div>

                <div id="addExpenseSection" style="display: none;">
                    <form id="expenseForm">
                        @csrf
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="description" class="form-label">Description</label>
                                <input type="text" class="form-control" id="description" name="description"
                                       required>
                            </div>
                            <div class="col-md-6">
                                <label for="amount" class="form-label">Amount</label>
                                <input type="number" step="0.01" class="form-control" id="amount" name="amount"
                                       required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="date" class="form-label">Date</label>
                                <input type="date" class="form-control" id="date" name="date" required>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-end gap-2">
                                    <div class="flex-grow-1">
                                        <label for="category" class="form-label">Category</label>
                                        <select class="form-select" id="category" name="category" required>
                                            <option value="">Select Category</option>
                                            @if(isset($categories) && count($categories))
                                                @foreach($categories as $category)
                                                    <option
                                                        value="{{ $category->id }}">{{ $category->name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                    <button type="button" class="btn btn-outline-primary" id="showCategoryListBtn">
                                        <i class="fa fa-plus"></i> New
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes (Optional)</label>
                            <textarea class="form-control" id="notes" name="notes" rows="2"></textarea>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="button" id="cancelBtn" class="btn btn-secondary me-2">Cancel</button>
                            <button type="submit" class="btn btn-primary">Save Expense</button>
                        </div>
                    </form>
                </div>

                <div id="categoryListSection" style="display: none;">
                    <div class="d-flex justify-content-between mb-3">
                        <button id="showExpensesList" class="btn btn-info btn-sm">
                            <i class="fa fa-list"></i> View Expenses
                        </button>
                        <button id="addCategoryFormBtn" class="btn btn-primary btn-sm">
                            <i class="fa fa-plus"></i> Add Category
                        </button>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped" id="categoryListTable">
                            <thead>
                            <tr>
                                <th width="80%">Name</th>
                                <th width="20%">Actions</th>
                            </tr>
                            </thead>
                            <tbody id="categoryTableBody">
                            </tbody>
                        </table>
                    </div>
                </div>

                <div id="addCategorySection" style="display: none;">
                    <form id="categoryForm">
                        @csrf
                        <div class="row mb-3">
                            <div class="mb-3">
                                <label for="categoryName" class="form-label">Category Name</label>
                                <input type="text" class="form-control" id="categoryName" name="categoryName" required>
                                <input type="hidden" id="categoryId" name="categoryId" value="">
                            </div>
                            <div class="d-flex justify-content-end">
                                <button type="button" id="cancelCategoryAdd" class="btn btn-secondary me-2">Close
                                </button>
                                <button type="submit" id="saveOrUpdateCategoryBtn" class="btn btn-primary">Save
                                    Category
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-loading-overlay" id="modalOverlay" style="display: none">
                <div class="spinner-border spinner-border-sm text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        </div>
    </div>
</div>


@push('scripts')
    <script>
        $(document).ready(function () {
            // Initialize modal
            const expenseModal = new bootstrap.Modal(document.getElementById('expenseModal'));

            // Open modal button
            $('#openExpenseModalBtn').click(function () {
                expenseModal.show();
                // loadExpenses();
            });

            // Set today's date as default
            $('#date').val(new Date().toISOString().substr(0, 10));

            // Toggle between views
            $('#addExpenseBtn').click(function () {
                $('#expenseListSection').hide();
                $('#addExpenseSection').show();
                $('#expenseModalTitle').text('Add New Expense');
            });

            $('#viewExpensesBtn, #cancelBtn').click(function () {
                $('#addExpenseSection').hide();
                $('#expenseListSection').show();
                $('#expenseModalTitle').text('Expense List');

                $('#search').val("");
                $('#from_date').val("");
                $('#to_date').val("");
                loadExpenses();
            });

            // Load expenses via AJAX
            function loadExpenses(page = 1, first = false, search = '') {
                if (first) {
                    $('#expanseLoading').show();
                }

                $('#expensesTableBody').html('<tr><td colspan="6" class="text-center">Loading...</td></tr>');
                const from_date = $('#from_date').val();
                const to_date = $('#to_date').val();

                $.ajax({
                    url: `/expenses/ajax?search=${search}&from_date=${from_date}&to_date=${to_date}&page=${page}`,
                    method: 'GET',
                    success: function (response) {
                        $('#expanseLoading').hide();

                        if (response.data.length > 0) {
                            let html = '';
                            response.data.forEach(expense => {
                                html += `
    <tr>
        <td>${new Date(expense.date).toLocaleDateString()}</td>
        <td>${expense?.description}</td>
        <td>${expense?.category?.name || ""}</td>
        <td>${parseFloat(expense.amount).toFixed(2)}</td>
        <td class="notes-cell">${expense.notes || '-'}</td>
        <td>
            <button class="btn btn-sm btn-danger delete-expense" data-id="${expense.id}">
                <i class="fa fa-trash"></i>
            </button>
        </td>
    </tr>
    `;
                            });
                            $('#expensesTableBody').html(html);

                            // Update pagination
                            let pagination = '';
                            if (response.last_page > 1) {
                                pagination += `<nav><ul class="pagination">`;

                                if (response.current_page > 1) {
                                    pagination += `<li class="page-item"><a class="page-link" href="#" data-page="${response.current_page - 1}">Previous</a></li>`;
                                }

                                for (let i = 1; i <= response.last_page; i++) {
                                    pagination += `<li class="page-item ${i === response.current_page ? 'active' : ''}">
                <a class="page-link" href="#" data-page="${i}">${i}</a>
            </li>`;
                                }

                                if (response.current_page < response.last_page) {
                                    pagination += `<li class="page-item"><a class="page-link" href="#" data-page="${response.current_page + 1}">Next</a></li>`;
                                }

                                pagination += `</ul></nav>`;
                            }
                            $('#expensePagination').html(pagination);

                        } else {
                            $('#expensesTableBody').html('<tr><td colspan="6" class="text-center">No expenses found</td></tr>');
                        }

                        // Update summary
                        $('#expanseSummery').text(response.total.toFixed(2));
                        $('#expanseResultValue').val(response.total.toFixed(2));
                        calculateEBITA();
                    },
                    error: function () {
                        $('#expensesTableBody').html('<tr><td colspan="6" class="text-center">Error loading expenses</td></tr>');
                    }
                });
            }

            // Debounce function
            function debounce(func, timeout = 500) {
                let timer;
                return (...args) => {
                    clearTimeout(timer);
                    timer = setTimeout(() => {
                        func.apply(this, args);
                    }, timeout);
                };
            }

            // Search input handler
            const handleSearch = debounce((searchTerm) => {
                loadExpenses(1, false, searchTerm); // Assuming you have pagination
            });


            // Event listener for search input
            $('#search').on('input', function () {
                const searchTerm = $(this).val().trim();
                handleSearch(searchTerm);
            });

            // Event listener for search input
            $('#filterExpanse').on('click', function () {
                loadExpenses(1);
            });

            // Handle pagination clicks
            $(document).on('click', '.page-link', function (e) {
                e.preventDefault();
                loadExpenses($(this).data('page'));
            });

            // Handle form submission
            $('#expenseForm').submit(function (e) {
                e.preventDefault();

                const loadingOverlay = $("#modalOverlay");
                loadingOverlay.show();

                $.ajax({
                    url: '{{ route('admin.expenses.save') }}',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: $(this).serialize(),
                    beforeSend: function () {
                        // Disable form elements
                        $('#expenseForm').find('input, button, textarea, select').prop('disabled', true);
                    },
                    success: function (response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: 'Expense added successfully!',
                            confirmButtonColor: '#3085d6',
                        }).then(() => {
                            $('#expenseForm')[0].reset();
                            $('#date').val(new Date().toISOString().substr(0, 10));
                            $('#addExpenseSection').hide();
                            $('#expenseListSection').show();
                            loadExpenses();
                        });
                    },
                    error: function (xhr) {
                        let errorMsg = 'An error occurred';
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            errorMsg = Object.values(xhr.responseJSON.errors).join('<br>');
                        }

                        loadingOverlay.hide();

                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            html: errorMsg,
                            confirmButtonColor: '#d33',
                        });
                    },
                    complete: function () {
                        // Remove overlay and re-enable form
                        loadingOverlay.hide();
                        $('#expenseForm').find('input, button, textarea, select').prop('disabled', false);
                    }
                });
            });

            // Handle delete expense
            $(document).on('click', '.delete-expense', function () {
                const expenseId = $(this).data('id');

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You want to delete this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    reverseButtons: true,
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            url: '/expenses/' + expenseId,
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            beforeSend: function () {
                                Swal.fire({
                                    title: 'Deleting...',
                                    html: 'Please wait while we delete the expense',
                                    allowOutsideClick: false,
                                    showConfirmButton: false, // This hides the OK button
                                    didOpen: () => {
                                        Swal.showLoading()
                                    }
                                });
                            },
                            success: function (response) {
                                Swal.fire(
                                    'Deleted!',
                                    'Your expense has been deleted.',
                                    'success'
                                );
                                loadExpenses();
                            },
                            error: function (xhr) {
                                Swal.fire(
                                    'Error!',
                                    'Failed to delete expense: ' + (xhr.responseJSON.message || 'Unknown error'),
                                    'error'
                                );
                            }
                        });
                    }
                });
            });

            loadExpenses(1, true);


            function updateCategoryUI(data) {
                let html = '';

                $('#category').empty().append('<option value="">Select Category</option>');
                data.forEach(category => {
                    $('#category').append(`<option value="${category.id}">${category.name}</option>`);

                    html += `<tr>
                                <td>${category?.name}</td>
                                <td>
                                    <button class="btn btn-sm btn-danger edit-expense-category" data-name="${category?.name}" data-id="${category?.id}">
                                        <i class="fa fa-pencil-square-o"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger delete-expense-category" data-name="${category?.name}" data-id="${category?.id}">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        `;
                });

                $('#categoryTableBody').html(html);
            }

            // Load categories on page load (optional)
            function loadCategories() {
                $.get('{{ route("admin.expenses.category.list") }}', function (data) {
                    updateCategoryUI(data);
                });
            }

            loadCategories();


            // Add expanse category
            $('#showCategoryListBtn').click(function () {
                $('#expenseListSection').hide();
                $('#addExpenseSection').hide();
                $('#expanseTopNav').hide();
                $('#categoryListSection').show();
                $('#expenseModalTitle').text('Expense Category');

                loadCategoryList()
            });

            // Load expenses via AJAX
            function loadCategoryList(first = false) {
                if (first) {
                    $('#expanseLoading').show();
                }

                $('#categoryTableBody').html('<tr><td colspan="2" class="text-center">Loading...</td></tr>');

                $.ajax({
                    url: '{{ route("admin.expenses.category.list") }}',
                    method: 'GET',
                    success: function (response) {
                        $('#expanseLoading').hide();

                        if (response?.length > 0) {
                            updateCategoryUI(response);
                        } else {
                            $('#categoryTableBody').html('<tr><td colspan="2" class="text-center">No category found</td></tr>');
                        }
                    },
                    error: function () {
                        $('#categoryTableBody').html('<tr><td colspan="2" class="text-center">Error loading expenses</td></tr>');
                    }
                });
            }

            // Save new category
            $('#saveCategoryBtn').click(function () {
                const name = $('#categoryName').val();

                if (!name) {
                    alert('Category name is required');
                    return;
                }

                $.ajax({
                    url: '{{ "admin.save.expenses.category" }}',
                    method: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        name: name
                    },
                    success: function (response) {
                        // Close modal and reset form
                        $('#categoryModal').modal('hide');
                        $('#categoryForm')[0].reset();

                        toastr.success('Category added successfully');
                    },
                    error: function (xhr) {
                        toastr.error(xhr.responseJSON.message || 'Error adding category');
                    }
                });
            });


            // Show expanse category
            $('#showExpensesList').click(function () {
                $('#expenseListSection').show();
                $('#addExpenseSection').hide();
                $('#expanseTopNav').show();
                $('#categoryListSection').hide();
                $('#addCategorySection').hide();
                $('#expenseModalTitle').text('Expense List');
                loadExpenses();
            });

            // Show expanse category Form
            $('#addCategoryFormBtn').click(function () {
                $('#expenseListSection').hide();
                $('#addExpenseSection').hide();
                $('#expanseTopNav').hide();
                $('#categoryListSection').hide();
                $('#addCategorySection').show();
                $('#categoryName').val("");
                $('#expenseModalTitle').text('Add Expense Category');
                $('#categoryForm').find('input, button').prop('disabled', false);
            });

            // Show expanse category list
            $('#cancelCategoryAdd').click(function () {
                showExpanseCategoryList();
            });

            function showExpanseCategoryList() {
                $('#expenseListSection').hide();
                $('#addExpenseSection').hide();
                $('#expanseTopNav').hide();
                $('#categoryListSection').show();
                $('#addCategorySection').hide();
                $('#expenseModalTitle').text('Expense Category');

                loadCategoryList()
            }

            // Save or update expanse category
            $('#categoryForm').submit(function (e) {
                e.preventDefault();
                const categoryName = $('#categoryName').val();
                const category_id = $('#category_id').val();

                if (categoryName === "") {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        html: "Category name is required!",
                        confirmButtonColor: '#d33',
                    });
                }

                $.ajax({
                    url: '{{ route('admin.save.expenses.category') }}',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: $(this).serialize(),
                    beforeSend: function () {
                        // Disable form elements
                        $('#categoryForm').find('input, button').prop('disabled', true);
                    },
                    success: function (response) {
                        let msg = "Category updated successfully!";
                        if (category_id === "") {
                            let msg = "Category added successfully!";
                        }

                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: msg,
                            confirmButtonColor: '#3085d6',
                        }).then(() => {
                            $('#categoryForm')[0].reset();

                            showExpanseCategoryList();
                        });
                    },
                    error: function (xhr) {
                        let errorMsg = 'An error occurred';
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            errorMsg = Object.values(xhr.responseJSON.errors).join('<br>');
                        }

                        loadingOverlay.remove();
                        $('#categoryForm').find('input, button').prop('disabled', false);

                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            html: errorMsg,
                            confirmButtonColor: '#d33',
                        });
                    },
                    complete: function () {
                        // Remove overlay and re-enable form
                        loadingOverlay.remove();
                        $('#categoryForm').find('input, button').prop('disabled', false);
                    }
                });

            });


            // Replace all your click handlers with these:

            // Edit Category (using event delegation)
            $(document).on('click', '.edit-expense-category', function () {
                const categoryId = $(this).data('id');
                const categoryName = $(this).data('name');

                // Set values in the edit modal
                $('#expenseListSection').hide();
                $('#addExpenseSection').hide();
                $('#expanseTopNav').hide();
                $('#categoryListSection').hide();
                $('#addCategorySection').show();
                $('#categoryId').val(categoryId);
                $('#categoryName').val(categoryName);
                $('#expenseModalTitle').text('Edit Category');

                $('#categoryForm').find('input, button').prop('disabled', false);

                $('#saveOrUpdateCategoryBtn').text('Update Category');
            });


            // Delete Category Button Click Handler (with button state management)
            $(document).on('click', '.delete-expense-category', function () {
                const $deleteBtn = $(this); // Store reference to the clicked button
                const categoryId = $deleteBtn.data('id');
                const categoryName = $deleteBtn.data('name');

                // Disable both buttons in the row
                disableRowButtons($deleteBtn);

                Swal.fire({
                    title: 'Are you sure?',
                    text: `Delete "${categoryName}"? This cannot be undone!`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Delete',
                    reverseButtons: true
                }).then((result) => {
                    if (!result.value) {
                        // Re-enable if user cancels
                        enableRowButtons($deleteBtn);
                        return;
                    }

                    $.ajax({
                        url: `/delete/expenses/categories/${categoryId}`,
                        method: 'DELETE',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        beforeSend: function () {
                            Swal.showLoading();
                        },
                        success: function (response) {
                            $deleteBtn.closest('tr').remove();

                            loadCategories();
                            Swal.fire('Deleted!', 'Category removed successfully', 'success');
                        },
                        error: function (xhr) {
                            const errorMsg = xhr.responseJSON?.message || 'Deletion failed';
                            Swal.fire('Error!', errorMsg, 'error');
                            enableRowButtons($deleteBtn);
                        },
                        complete: function () {
                            // Re-enable button after request completes (success or error)
                            // enableRowButtons($deleteBtn);
                        }
                    });
                });
            });


            // Helper function to reset button state
            // Function to disable both buttons in a row
            function disableRowButtons($button) {
                const $row = $button.closest('tr');
                $row.find('.edit-expense-category, .delete-expense-category')
                    .prop('disabled', true)
                    .html('<i class="fa fa-spinner fa-spin"></i>');
            }

            // Function to enable both buttons in a row
            function enableRowButtons($button) {
                const $row = $button.closest('tr');
                $row.find('.edit-expense-category')
                    .prop('disabled', false)
                    .html('<i class="fa fa-pencil-square-o"></i>');
                $row.find('.delete-expense-category')
                    .prop('disabled', false)
                    .html('<i class="fa fa-trash"></i>');
            }

        });

    </script>
@endpush
