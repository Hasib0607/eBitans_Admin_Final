@extends('admin.layouts.main')
@section('content')
    <?php
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
                } elseif ($pr == 'testimonials') {
                    $tt = 1;
                } else {
                }
            }
        }
    } else {
        $store_id = 0;
    }
    if ($store_id != 0) {
        $store = DB::table('stores')
            ->where('id', $store_id)
            ->first();
        if ($store->expiry_date <= Carbon\Carbon::now()) {
            $exp = 1;
        } else {
            $exp = 0;
        }
    }
    ?>
    @if (Auth::user()->type == 'staff')
            <?php
            $stafff = DB::table('staff')
                ->where('uid', Auth::user()->id)
                ->first();
            if (isset($stafff)) {
                if (isset($stafff->pos)) {
                    $staff_pos = 1;
                } else {
                    $staff_pos = 0;
                }
            } else {
                $staff_pos = 0;
            }
            ?>
    @endif

    <main class="main-content position-relative border-radius-lg">
        <div class="container-fluid navbars"
             style="background-image: linear-gradient(53deg, #42424a 0%, #191919 100%);">
            <div class="row">
                <div class="col-md-12">
                    <nav style="--bs-breadcrumb-divider: '';" aria-label="breadcrumb">
                        <ol class="breadcrumb" style="background-color:transparent;color:#fff">
                            <li class="breadcrumb-item active">
                                <a href="{{ URL::to('/') }}/branch">
                                    <img src="{{ URL::to('/') }}/img/icons/branch.png"> <br> <span
                                        class="nav-link-text ms-1">
                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                            ব্রাঞ্চ
                                        @else
                                            Branches
                                        @endif
                                    </span>
                                </a>
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <div class="container-fluid mt-4" id="toplist">
            <div class="row">
                <div class="col-md-6">
                    <h4>All Branch</h4>
                </div>
                <div class="col-md-6">
                    @if ((isset($branch) && $branch == '1') || Auth::user()->type == 'admin' || Auth::user()->type == 'dropshipper')
                        <ul>
                            @if (count($branchs) == $plan->branch)
                            @else
                                <li style="padding:0px;border:0px;"><a href="{{ route('admin.branch.create') }}"
                                                                       style="display:block;border-radius:0px !important"
                                                                       class="btn btn-primary"> Create
                                        New</a></li>
                            @endif

                            <li style="padding:0px;border:0px;"><a data-href="{{ route('admin.productExportCsv') }}"
                                                                   onclick="htmlTableToExcel('xlsx')"
                                                                   style="display:block;border-radius:0px !important"
                                                                   class="btn btn-secondary">Excel</a>

                            </li>
                        </ul>
                    @else
                    @endif
                </div>
            </div>
            <div class="row productlist">
                <div class="col-12">
                    <div class="alert alert-info"
                         style="background-image: linear-gradient(195deg, #b5b5b5 0%, #485059 100%);" role="alert">
                        <span
                            style="color:#fff">Total Branch add {{ count($branchs) ?? '0' }}/{{ $plan->branch }}</span>
                    </div>
                </div>
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-2" style="padding-right:1px;">
                                    <form id="submitform" method="post"
                                          action="{{ route('admin.changebranchssstatus') }}">
                                        @csrf
                                        <input type="hidden" name="text2" id="selectids">
                                        <select class='form-control' name="action" id="action">
                                            <option value="select">Select Option</option>
                                            <option value="active">Active</option>
                                            <option value="deactive">Deactive</option>
                                            <option value="delete">Delete</option>
                                        </select>
                                </div>
                                <div class="col-md-1" style="padding-left:0px;">
                                    <p id="submit" class="btn btn-primary filterbuttonss">Apply</p>
                                    </form>
                                </div>
                                <div class="col-md-7"></div>
                                <div class="col-md-2">
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
                                <div class="alert alert-success">{{ Session::get('success_message') }}</div>
                            @endif
                            <div class="table-responsive" id="desktoptable">
                                <table class="table table-striped" width="100%">
                                    <thead>
                                    <tr>
                                        <th width="4%"><input type="checkbox" name="ids" id="checkedAll"></th>
                                        <th width="5%">Branch Name</th>
                                        <th width="15%">Email</th>
                                        <th width="10%">Phone</th>
                                        <th width="15%">Address</th>
                                        <th width="10%">Status</th>
                                        <th width="15%">Date</th>
                                        <th width="11%">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    if (Auth::user()->type == 'staff') {
                                        $staff = DB::table('staff')
                                            ->where('uid', Auth::user()->id)
                                            ->first();
                                    }
                                    ?>
                                    @foreach ($branchs as $branchss)
                                        @if (Auth::user()->type == 'staff')
                                                <?php
                                                $stfs = DB::table('staff')
                                                    ->where('id', $staff->id)
                                                    ->whereRaw('FIND_IN_SET("' . $branchss->id . '", pos)')
                                                    ->first();
                                                ?>
                                        @endif
                                        @if ((isset($branch) && $branch == '1') || isset($stfs) || Auth::user()->type == 'admin' || Auth::user()->type == 'dropshipper')
                                            <tr style="border-bottom:1px solid rgba(0, 0, 0, 0.125)">
                                                <td><input type="checkbox" name="selectedid" value="{{ $branchss->id }}"
                                                           id="id" class="checkSingle"></td>
                                                <td>{{ $branchss->name }}</td>
                                                <td>{{ $branchss->email }}</td>
                                                <td>{{ $branchss->phone }}</td>
                                                    <?php
                                                    $customer = DB::table('customers')
                                                        ->where('id', $branchss->customer_id)
                                                        ->first();
                                                    ?>
                                                <td>{{ $branchss->address }}</td>
                                                <td>{{ $branchss->status }}</td>
                                                <td>{{ $branchss->created_at }}</td>
                                                <td>

                                                    <!--<a href="{{ route('admin.branch.pos', encrypt($branchss->id)) }}" class="btn btn-success">Pos</a>-->


                                                    @if (Auth::user()->type == 'admin' || Auth::user()->type == 'dropshipper' || isset($stfs))
                                                        <a href="{{ route('admin.branch.pos', encrypt($branchss->id)) }}"
                                                           class="btn btn-success sm mb-0">Pos</a>
                                                    @endif
                                                    @if ((isset($branch) && $branch == '1') || Auth::user()->type == 'admin' || Auth::user()->type == 'dropshipper')
                                                        <a
                                                            href="{{ route('admin.addproductbranch', $branchss->id) }}"
                                                            class="btn btn-success sm mb-0 mr-2 ml-2 pt-1 pb-1"><img
                                                                src="{{ asset('img/add-product.png') }}" width="30px"
                                                                height="30px"></a>

                                                        <a
                                                            href="{{ route('admin.editbranch', $branchss->id) }}"
                                                            class="btn btn-success sm mb-0 mr-2 pt-2 pb-2"><img
                                                                src="{{ asset('img/edit.png') }}" width="20px"
                                                                height="20px"></a>
                                                        <a href="{{ route('admin.deletebranch', $branchss->id) }}"
                                                           class="btn btn-danger sm mb-0 pt-2 pb-2"
                                                           onclick="return confirm('Are you sure you want to delete this item?');"><img
                                                                src="{{ asset('img/delete.png') }}" width="25px"
                                                                height="25px"></a>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="table-responsive" id="mobiletable">
                                <table class="table" width="100%">
                                    <?php
                                    if (Auth::user()->type == 'staff') {
                                        $staff = DB::table('staff')
                                            ->where('uid', Auth::user()->id)
                                            ->first();
                                    }
                                    ?>
                                    @foreach ($branchs as $key => $branchss)
                                        @if (Auth::user()->type == 'staff')
                                                <?php
                                                $stfs = DB::table('staff')
                                                    ->where('id', $staff->id)
                                                    ->whereRaw('FIND_IN_SET("' . $branchss->id . '", pos)')
                                                    ->first();
                                                ?>
                                        @endif
                                        @if ((isset($branch) && $branch == '1') || isset($stfs) || Auth::user()->type == 'admin' || Auth::user()->type == 'dropshipper')
                                            <tr class="mobilefirstrow">
                                                <th width="10%">
                                                    <input type="checkbox" name="selectedid" value="{{ $branchss->id }}"
                                                           id="id" class="checkSingle">
                                                </th>
                                                <th width="20%" style="color:#f1593a">
                                                    Branch Name
                                                </th>
                                                <td width="60%" style="color:black">
                                                    {{ $branchss->name }}
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
                                                    Email
                                                </th>
                                                <td width="60%">
                                                    <!--{{ $branchss->email }}-->
                                                </td>
                                                <td width="10%"></td>
                                            </tr>
                                            <tr class="cat{{ $key }}" style="display:none">
                                                <th width="10%"></th>
                                                <th width="20%">
                                                    Phone
                                                </th>
                                                <td width="60%">
                                                    {{ $branchss->phone }}
                                                </td>
                                                <td width="10%"></td>
                                            </tr>
                                            <tr class="cat{{ $key }}" style="display:none">
                                                <th width="10%"></th>
                                                <th width="20%">
                                                    Company<br> Name
                                                </th>
                                                <td width="60%">
                                                        <?php
                                                        $customer = DB::table('customers')
                                                            ->where('id', $branchss->customer_id)
                                                            ->first();
                                                        ?>
                                                    {{ $customer->company_name ?? '' }}
                                                </td>
                                                <td width="10%"></td>
                                            </tr>
                                            <tr class="cat{{ $key }}" style="display:none">
                                                <th width="10%"></th>
                                                <th width="20%">
                                                    Address
                                                </th>
                                                <td width="60%">
                                                    {{ $branchss->address }}
                                                </td>
                                                <td width="10%"></td>
                                            </tr>
                                            <tr class="cat{{ $key }}" style="display:none">
                                                <th width="10%"></th>
                                                <th width="20%">
                                                    Status
                                                </th>
                                                <td width="60%">
                                                    {{ $branchss->status }}
                                                </td>
                                                <td width="10%"></td>
                                            </tr>
                                            <tr class="cat{{ $key }}" style="display:none">
                                                <th width="10%"></th>
                                                <th width="20%">
                                                    Date
                                                </th>
                                                <td width="60%">
                                                    {{ $branchss->created_at }}
                                                </td>
                                                <td width="10%"></td>
                                            </tr>
                                            <tr class="cat{{ $key }}" style="display:none">
                                                <th width="10%"></th>
                                                <th width="20%">
                                                    Action
                                                </th>
                                                <td width="60%">

                                                    @if (Auth::user()->type == 'admin' || Auth::user()->type == 'dropshipper' || isset($stfs))
                                                        <a href="{{ route('admin.branch.pos', encrypt($branchss->id)) }}"
                                                           class="btn btn-success">Pos</a>
                                                    @endif
                                                    @if ((isset($branch) && $branch == '1') || Auth::user()->type == 'admin' || Auth::user()->type == 'dropshipper')
                                                        &nbsp;&nbsp;
                                                        <a href="{{ route('admin.addproductbranch', $branchss->id) }}"><img
                                                                src="{{ asset('img/add-product.png') }}" width="25px"
                                                                height="25px"</a>
                                                        &nbsp;&nbsp;
                                                        <br>
                                                        <a href="{{ route('admin.editbranch', $branchss->id) }}"><img
                                                                src="{{ asset('img/edit.png') }}" width="20px"
                                                                height="20px"></a>
                                                        &nbsp;&nbsp;
                                                        <a href="{{ route('admin.deletebranch', $branchss->id) }}"
                                                           onclick="return confirm('Are you sure you want to delete this item?');"><img
                                                                src="{{ asset('img/delete.png') }}" width="25px"
                                                                height="25px"</a>
                                                    @endif
                                                </td>
                                                <td width="10%"></td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>











    {{-- hidden part  --}}
    <div style="display: none;" class="table-responsive" id="desktoptable">
        <table id="tblToExcl" class="table table-striped" width="100%">
            <thead>
            <tr>
                <th width="5%">Branch Name</th>
                <th width="15%">Email</th>
                <th width="10%">Phone</th>
                <th width="15%">Address</th>
                <th width="10%">Status</th>
                <th width="15%">Date</th>
            </tr>
            </thead>
            <tbody>
            <?php
            if (Auth::user()->type == 'staff') {
                $staff = DB::table('staff')
                    ->where('uid', Auth::user()->id)
                    ->first();
            }
            ?>
            @foreach ($branchs as $branchss)
                @if (Auth::user()->type == 'staff')
                        <?php
                        $stfs = DB::table('staff')
                            ->where('id', $staff->id)
                            ->whereRaw('FIND_IN_SET("' . $branchss->id . '", pos)')
                            ->first();
                        ?>
                @endif
                @if ((isset($branch) && $branch == '1') || isset($stfs) || Auth::user()->type == 'admin' || Auth::user()->type == 'dropshipper')
                    <tr style="border-bottom:1px solid rgba(0, 0, 0, 0.125)">

                        <td>{{ $branchss->name }}</td>
                        <td>{{ $branchss->email }}</td>
                        <td>{{ $branchss->phone }}</td>
                            <?php
                            $customer = DB::table('customers')
                                ->where('id', $branchss->customer_id)
                                ->first();
                            ?>
                        <td>{{ $branchss->address }}</td>
                        <td>{{ $branchss->status }}</td>
                        <td>{{ $branchss->created_at }}</td>

                    </tr>
                @endif
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
@push('scripts')
    <script type="text/javascript" src="https://unpkg.com/xlsx@0.15.1/dist/xlsx.full.min.js"></script>


    <script>
        function htmlTableToExcel(type) {
            var data = document.getElementById('tblToExcl');
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
        $(document).ready(function () {
            $("#checkedAll").change(function () {
                debugger;
                if (this.checked) {
                    $(".checkSingle").each(function () {
                        debugger;
                        this.checked = true;
                        var valuesArray = $('input[name="selectedid"]:checked').map(function () {
                            return this.value;
                        }).get().join(",");
                        $("#selectids").val(valuesArray);
                        $("#selectdelids").val(valuesArray);
                    });
                } else {
                    $(".checkSingle").each(function () {
                        this.checked = false;
                    });
                    var valuesArray = '';
                    $("#selectids").val(valuesArray);
                    $("#selectdelids").val(valuesArray);
                }
            });
            $(".checkSingle").click(function () {
                if ($(this).is(":checked")) {
                    var isAllChecked = 0;
                    $(".checkSingle").each(function () {
                        if (!this.checked)
                            isAllChecked = 1;
                        var valuesArray = $('input[name="selectedid"]:checked').map(function () {
                            return this.value;
                        }).get().join(",");
                        $("#selectids").val(valuesArray);
                        $("#selectdelids").val(valuesArray);
                    });
                    if (isAllChecked == 0) {
                        $("#checkedAll").prop("checked", true);
                    }
                } else {
                    $("#checkedAll").prop("checked", false);
                    var valuesArray = $('input[name="selectedid"]:checked').map(function () {
                        return this.value;
                    }).get().join(",");
                    $("#selectids").val(valuesArray);
                    $("#selectdelids").val(valuesArray);
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

        function exportTasks(_this) {
            let _url = $(_this).data('href');
            window.location.href = _url;
        }

        localStorage.removeItem('bid');
        localStorage.removeItem('cartsessionid');
    </script>
@endpush
