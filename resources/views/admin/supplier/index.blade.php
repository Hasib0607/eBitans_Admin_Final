@extends('admin.layouts.main')
@section('content')
    @php
        if (Auth::user()->type == 'admin' || Auth::user()->type == 'dropshipper') {
            $customer = DB::table('customers')
                ->where('uid', Auth::user()->id)
                ->first();
            $store_id = $customer->active_store;
        } elseif (Auth::user()->type == 'staff') {
            $staff = DB::table('staff')
                ->where('uid', Auth::user()->id)
                ->first();
            $store_id = $staff->store_id;
            $role = DB::table('roles')
                ->where('id', $staff->role_id)
                ->first();
            if (isset($role)) {
                $permission = explode(',', $role->permission);
                foreach ($permission as $key => $pr) {
                    if ($pr == 'branch') {
                        $branch = 1;
                    } elseif ($pr == 'product') {
                        $product = 1;
                    } elseif ($pr == 'category') {
                        $category = 1;
                    } elseif ($pr == 'subcategory') {
                        $subcategory = 1;
                    } elseif ($pr == 'brand') {
                        $brand = 1;
                    } elseif ($pr == 'attribute') {
                        $attribute = 1;
                    } elseif ($pr == 'supplier') {
                        $supplier = 1;
                    } elseif ($pr == 'collection') {
                        $collection = 1;
                    } elseif ($pr == 'global_tab') {
                        $global_tab = 1;
                    } elseif ($pr == 'coupon') {
                        $coupon = 1;
                    } elseif ($pr == 'campaign') {
                        $campaign = 1;
                    } elseif ($pr == 'offer') {
                        $offer = 1;
                    } elseif ($pr == 'slider') {
                        $slider = 1;
                    } elseif ($pr == 'banner') {
                        $banner = 1;
                    } elseif ($pr == 'layouts') {
                        $layouts = 1;
                    } elseif ($pr == 'template') {
                        $template = 1;
                    } elseif ($pr == 'header') {
                        $header = 1;
                    } elseif ($pr == 'homepage') {
                        $homepage = 1;
                    } elseif ($pr == 'footer') {
                        $footer = 1;
                    } elseif ($pr == 'mobilemenu') {
                        $mobilemenu = 1;
                    } elseif ($pr == 'product_display') {
                        $product_display = 1;
                    } elseif ($pr == 'product_grid') {
                        $product_grid = 1;
                    } elseif ($pr == 'shop_page') {
                        $shop_page = 1;
                    } elseif ($pr == 'pages') {
                        $pages = 1;
                    } elseif ($pr == 'customer') {
                        $customer = 1;
                    } elseif ($pr == 'staff') {
                        $staff = 1;
                    } elseif ($pr == 'invoice') {
                        $invoice = 1;
                    } elseif ($pr == 'setting') {
                        $setting = 1;
                    } elseif ($pr == 'role_permission') {
                        $role_permission = 1;
                    } elseif ($pr == 'pos') {
                        $pos = 1;
                    } else {
                    }
                }
            }
        }
        $store = DB::table('stores')->where('id', $store_id)->first();
        if ($store->expiry_date <= Carbon\Carbon::now()) {
            $exp = 1;
        } else {
            $exp = 0;
        }
    @endphp
    <main class="main-content position-relative h-100 border-radius-lg">
        @include('admin.admin_top_bar_category.index')
        <div class="container-fluid mt-4" id="toplist">
            <div class="row">
                <div class="col-md-6">
                    <h4>
                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                            সব সরবরাহকারী
                        @else
                            All Supplier
                        @endif
                    </h4>
                </div>
                <div class="col-md-6">
                    <ul>
                        <li style="padding:0px;border:0px;"><a data-href="/supplierexport"
                                                               onclick="exportSupplier(event.target);"
                                                               style="display:block;border-radius:0px !important"
                                                               class="btn btn-secondary">
                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                    এক্সপোর্ট
                                @else
                                    Excel
                                @endif
                            </a></li>
                    </ul>
                </div>
            </div>
            <div class="row mt-5 productlist">
                <div class="col-lg-4 col-md-12 col-sm-12 mt-4">
                    <div class="card">
                        <div class="card-header">
                            <h4>
                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                    নতুন সরবরাহকারী যোগ করুন
                                @else
                                    Add Supplier
                                @endif
                            </h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ URL::to('supplier') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3 row">
                                    <label for="staticEmail" class="col-md-6 col-form-label">
                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                            নাম
                                        @else
                                            Name
                                        @endif
                                        <span class="req">*</span>
                                    </label>
                                    <div class="col-md-11">
                                        <input type="text" class="form-control" id="staticEmail" name="name"
                                               placeholder="Name">
                                        @error('name')
                                        <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="staticEmail" class="col-md-6 col-form-label">
                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                            কোমপানির নাম
                                        @else
                                            Company Name
                                        @endif
                                    </label>
                                    <div class="col-md-11">
                                        <input type="text" class="form-control" id="staticEmail" name="company_name"
                                               placeholder="Name">
                                        @error('company_name')
                                        <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="staticEmail" class="col-md-6 col-form-label">
                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                            ফোন
                                        @else
                                            Phone
                                        @endif
                                        <span class="req">*</span>
                                    </label>
                                    <div class="col-md-11">
                                        <input type="number" class="form-control" id="staticEmail" name="phone"
                                               placeholder="phone">
                                        @error('phone')
                                        <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="staticEmail" class="col-md-6 col-form-label">
                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                            ঠিকানা
                                        @else
                                            Address
                                        @endif
                                    </label>
                                    <div class="col-md-11">
                                        <input type="text" class="form-control" id="staticEmail" name="address"
                                               placeholder="address">
                                        @error('address')
                                        <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="position" class="col-md-3 col-form-label"></label>
                                    <div class="col-md-8" style="text-align:right">
                                        <button type="submit" id="submitBtn" class="btn btn-info">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                জমা দিন
                                            @else
                                                Submit
                                            @endif
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8 col-md-12 col-sm-12 mt-4">
                    <div class="card">
                        <div class="card-header">
                            @if (Session::has('success_message'))
                                <div class="alert alert-success">{{ Session::get('success_message') }}</div>
                            @endif
                            <div class="row">
                                <div class="col-md-2" style="padding-right:1px;">
                                    <form id="submitform" method="post"
                                          action="{{ route('admin.changesupplierstatus') }}">
                                        @csrf
                                        <input type="hidden" name="text2" id="selectids">
                                        <select class='form-control' name="action" id="action">
                                            <option value="select">
                                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                    সিলেক্ট অপসন
                                                @else
                                                    Select Option
                                                @endif
                                            </option>
                                            >
                                            <option value="delete">
                                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                    ডিলিট
                                                @else
                                                    Delete
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
                                    </form>
                                </div>
                                <div class="col-md-6"></div>
                                <div class="col-md-3">
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
                            <div class="table-responsive" id="desktoptable">
                                <table class="table table-striped" width="100%" id="taskfilterresult">
                                    <thead>
                                    <tr>
                                        <th width="10%"><input type="checkbox" name="ids" id="checkedAll">
                                        </th>
                                        <th width="20%">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                নাম
                                            @else
                                                Name
                                            @endif
                                        </th>
                                        <th width="20%">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                কোমপানির নাম
                                            @else
                                                Company Name
                                            @endif
                                        </th>
                                        <th width="15%">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                ফোন
                                            @else
                                                Phone
                                            @endif
                                        </th>
                                        <th width="20%">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                ঠিকানা
                                            @else
                                                Address
                                            @endif
                                        </th>
                                        <th width="10%">Product</th>
                                        <th width="15%">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                এডিট/ডিলিট
                                            @else
                                                Action
                                            @endif
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($suppliers as $supplier)
                                        <tr style="border-bottom:1px solid rgba(0, 0, 0, 0.125)">
                                            <td><input type="checkbox" name="selectedid" value="{{ $supplier->id }}"
                                                       id="id" class="checkSingle"></td>
                                            <td>{{ $supplier->name }}</td>
                                            <td>{{ $supplier->company_name }}</td>
                                            <td>{{ $supplier->phone }}</td>
                                            <td>{{ $supplier->address }}</td>
                                            <td><a href="{{ route('admin.supplier.product', $supplier->id) }}"
                                                   class="btn btn-secondary">View</a></td>
                                            <td>
                                                <a href="{{ URL::to('/') }}/supplier/{{ $supplier->id }}/edit"><img
                                                        src="{{ asset('img/edit.png') }}" width="20px"
                                                        height="20px"></a>
                                                &nbsp;&nbsp;
                                                <a href="{{ URL::to('/') }}/supplier/{{ $supplier->id }}/delete"
                                                   onclick="return confirm('Are you sure you want to delete this item?');"><img
                                                        src="{{ asset('img/delete.png') }}" width="25px"
                                                        height="25px"></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="table-responsive" id="mobiletable">
                                <table class="table" style="width:100%">
                                    @foreach ($suppliers as $key => $supplier)
                                        <tr class="mobilefirstrow">
                                            <th width="10%">
                                                <input type="checkbox" name="selectedid" value="{{ $supplier->id }}"
                                                       id="id" class="checkSingle">
                                            </th>
                                            <th width="20%" style="color:#f1593a">
                                                Name
                                            </th>
                                            <td width="60%" style="color:black">
                                                {{ $supplier->name }}
                                            </td>
                                            <td width="10%">
                                                <a href="#" class="toggler" data-prod-cat="{{ $key }}">
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
                                                Company Name
                                            </th>
                                            <td width="60%">
                                                {{ $supplier->company_name }}
                                            </td>
                                            <td width="10%"></td>
                                        </tr>
                                        <tr class="cat{{ $key }}" style="display:none">
                                            <th width="10%"></th>
                                            <th width="20%">
                                                Phone
                                            </th>
                                            <td width="60%">
                                                {{ $supplier->phone }}
                                            </td>
                                            <td width="10%"></td>
                                        </tr>
                                        <tr class="cat{{ $key }}" style="display:none">
                                            <th width="10%"></th>
                                            <th width="20%">
                                                Address
                                            </th>
                                            <td width="60%">
                                                {{ $supplier->address }}
                                            </td>
                                            <td width="10%"></td>
                                        </tr>
                                        <tr class="cat{{ $key }}" style="display:none">
                                            <th width="10%"></th>
                                            <th width="20%">
                                                Action
                                            </th>
                                            <td width="60%">
                                                <a href="{{ URL::to('/') }}/supplier/{{ $supplier->id }}/edit"><img
                                                        src="{{ asset('img/edit.png') }}" width="20px"
                                                        height="20px"></a>
                                                &nbsp;&nbsp;
                                                <a href="{{ URL::to('/') }}/supplier/{{ $supplier->id }}/delete"
                                                   onclick="return confirm('Are you sure you want to delete this item?');"><img
                                                        src="{{ asset('img/delete.png') }}" width="25px"
                                                        height="25px"></a>
                                            </td>
                                            <td width="10%"></td>
                                        </tr>
                                    @endforeach
                                </table>
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
        $('#submit').on('click', function () {
            var form = $(this).parents('form');
            var note = $('#action').val();
            if (note != 'select') {
                swal.fire({
                    title: 'Are you sure?',
                    text: "You want to " + note + " this selected item",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, ' + note + ' it!',
                    cancelButtonText: 'No, cancel!',
                    reverseButtons: true
                }).then((result) => {
                    if (result.value) {
                        console.log(form);
                        $('#submitform').submit();
                        form.submit();
                    } else if (
                        result.dismiss === Swal.DismissReason.cancel
                    ) {
                        swal.fire(
                            'Cancelled',
                            '' + note + ' Cancel :)',
                            'error'
                        )
                    }
                })
            }
        })


        $('#submitBtn').on('click', function () {
            this.disabled = true;
            this.form.submit();
        });


        $(document).ready(function () {
            let valuesArray = [];

            // Check all checkbox action
            $("#checkedAll").change(function () {
                if (this.checked) {
                    // If "checkedAll" is checked, check all ".checkSingle" checkboxes
                    $(".checkSingle").each(function () {
                        this.checked = true;
                        let value = $(this).val();
                        if (!valuesArray.includes(value)) {
                            valuesArray.push(value);
                        }
                    });
                } else {
                    // If "checkedAll" is unchecked, uncheck all ".checkSingle" checkboxes
                    $(".checkSingle").each(function () {
                        this.checked = false;
                    });
                    valuesArray = [];
                }

                let newAaluesArray = valuesArray.join(","); // Convert array to comma-separated string

                $("#selectids").val(newAaluesArray);
                $("#selectdelids").val(newAaluesArray);
            });

            // Single check action
            $(".checkSingle").click(function () {
                if ($(this).is(":checked")) {
                    let value = $(this).val();

                    let isAllChecked = $(".checkSingle").length === $(".checkSingle:checked").length;
                    $("#checkedAll").prop("checked", isAllChecked);

                    if (!valuesArray.includes(value)) {
                        valuesArray.push(value);
                    }

                    let newAaluesArray = valuesArray.join(","); // Convert array to comma-separated string

                    $("#selectids").val(newAaluesArray);
                    $("#selectdelids").val(newAaluesArray);
                } else {
                    $("#checkedAll").prop("checked", false);

                    let value = $(this).val();

                    let index = valuesArray.indexOf(value);

                    if (index === -1) {
                        valuesArray.push(value);
                    } else {
                        valuesArray.splice(index, 1);
                    }

                    let newAaluesArray = valuesArray.join(","); // Convert array to comma-separated string

                    $("#selectids").val(newAaluesArray);
                    $("#selectdelids").val(newAaluesArray);
                }

            });
        });

        $(document).ready(function () {
            $("#taskfilter").on("keyup", function () {
                debugger;
                var value = $(this).val().toLowerCase();
                debugger;
                $("#taskfilterresult tbody tr").filter(function () {
                    debugger;
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                    debugger;
                });
            });
        });

        function exportSupplier(_this) {
            let _url = $(_this).data('href');
            window.location.href = _url;
        }
    </script>
@endpush
