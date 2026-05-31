@extends('admin.layouts.main')

@push('styles')
    <style>
        .fade:not(.show) {
            opacity: 1 !important;
        }


        /* This is copied from https://github.com/blueimp/jQuery-File-Upload/blob/master/css/jquery.fileupload.css */
        .fileinput-button {
            position: relative;
            overflow: hidden;
        }

        #imgList {
            display: contents;
        }

        .fileinput-button input {
            position: absolute;
            top: 0;
            right: 0;
            margin: 0;
            opacity: 0;
            -ms-filter: "alpha(opacity=0)";
            font-size: 200px;
            direction: ltr;
            cursor: pointer;
        }

        .thumb {
            height: 80px;
            width: 100px;
            border: 1px solid #000;
        }

        ul.thumb-Images li {
            width: 120px;
            float: left;
            display: inline-block;
            vertical-align: top;
            height: 120px;
        }

        .img-wrap {
            position: relative;
            display: inline-block;
            font-size: 0;
        }

        .img-wrap .close {
            position: absolute;
            top: 2px;
            right: 2px;
            z-index: 100;
            background-color: #d0e5f5;
            padding: 5px 2px 2px;
            color: #000;
            font-weight: bolder;
            cursor: pointer;
            opacity: 0.5;
            font-size: 23px;
            line-height: 10px;
            border-radius: 50%;
        }

        .img-wrap:hover .close {
            opacity: 1;
            background-color: #ff0000;
        }

        .FileNameCaptionStyle {
            font-size: 12px;
        }
    </style>
@endpush

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
                } else {
                }
            }
        }
    } elseif (Auth::user()->type == 'superstaff') {
        $staff = Auth::user();
        $store_id = $staff->store_id ?? "";
        $store = \App\Models\Store::where("id", $store_id)->first();
        $user_id = $store->user_id ?? "";
        $user = \App\Models\User::where('id', $user_id)->first();
        $user_type = $user->type ?? "";
        $customer = \App\Models\Customer::where('uid', $user_id)->first();
        $customer_id = $customer->id;

        $superstaff = DB::table('superstaffs')
            ->where('uid', Auth::user()->id)
            ->first();
        $superrole = DB::table('superroles')
            ->where('id', $superstaff->role_id)
            ->first();

        $permission = explode(',', $superrole->permission);

        if (isset(Auth::user()->store_id) && !is_null(Auth::user()->store_id)) {
            $superrolePermission = DB::table('superstaff_permissions')
                ->where('role_id', $superstaff->role_id)
                ->first();
            $superPermission = explode(',', $superrolePermission->permission);

            // Merge both permission arrays
            $permission = array_merge($superPermission, $permission);
        }
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

    $store = DB::table('stores')
        ->where('id', $store_id)
        ->first();
    if ($store->expiry_date <= Carbon\Carbon::now()) {
        $exp = 1;
    } else {
        $exp = 0;
    }
    ?>
    <main class="main-content position-relative h-100 border-radius-lg">
        @include('admin.admin_top_bar_category.index')
        <div class="container-fluid mt-4" id="toplist">
            <div class="row">
                <div class="col-md-6">
                    <h4>
                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                            সব ব্রান্ড
                        @else
                            All Brands
                        @endif
                    </h4>
                </div>
                <div class="col-md-6">
                    <ul>
                        <li style="padding:0px;border:0px;"><a data-href="/brandexport"
                                                               onclick="exportBrand(event.target);"
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
                                    নতুন ব্র্যান্ড যোগ করুন
                                @else
                                    Add Brand
                                @endif
                            </h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ URL::to('brand') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3 row">
                                    <label for="staticEmail" class="col-md-3 col-form-label">
                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                            নাম
                                        @else
                                            Name
                                        @endif
                                        <span class="req">*</span>
                                    </label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" id="staticEmail" name="name"
                                               placeholder="Category Name">
                                        @error('name')
                                        <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-3 row">
                                    <label for="image" class="col-md-3 col-form-label">
                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                            ছবি
                                        @else
                                            Image
                                        @endif
                                    </label>
                                    <div class="col-md-8">
                                        <div id="previewContainer"></div>
                                        <input type="hidden" class="form-control" id="image" name="image">

                                        <button type="button" class="btn btn-outline-secondary browse-btn mt-2"
                                                onclick="standalonFileManagerModal('image', true, 'previewContainer');">
                                            <i class="fa fa-picture-o"></i> Browse
                                        </button>
                                        @error('image')
                                        <p class="text-danger" role="alert">{{ $message }}</p>
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
                                          action="{{ route('admin.changebrandstatus') }}">
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
                            <div class="table-responsive">
                                <table class="table table-striped" width="100%" id="taskfilterresult">
                                    <thead>
                                    <tr>
                                        <th width="10%"><input type="checkbox" name="ids" id="checkedAll">
                                        </th>
                                        <th width="30%">
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
                                        <th width="10%">Products</th>
                                        <th width="30%">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                এডিট/ডিলিট
                                            @else
                                                Action
                                            @endif
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($brands as $brand)
                                        <tr style="border-bottom:1px solid rgba(0, 0, 0, 0.125)">
                                            <td><input type="checkbox" name="selectedid" value="{{ $brand->id }}"
                                                       id="id" class="checkSingle"></td>
                                            <td>
                                                @if(!empty($brand->image))
                                                    <img src="{{ getPath($brand->image, "assets/images/brand") }}"
                                                         width="50px">
                                                @endif
                                            </td>
                                            <td>{{ $brand->name }}</td>
                                            <td><a href="{{ route('admin.brand.product', $brand->id) }}"
                                                   class="btn btn-secondary">View</a></td>
                                            <td>
                                                <a href="{{ URL::to('/') }}/brand/{{ $brand->id }}/edit"><img
                                                        src="{{ asset('img/edit.png') }}" width="20px"
                                                        height="20px"></a>
                                                &nbsp;&nbsp;
                                                <a href="{{ URL::to('/') }}/brand/{{ $brand->id }}/delete"
                                                   onclick="return confirm('Are you sure you want to delete this item?');"><img
                                                        src="{{ asset('img/delete.png') }}" width="25px"
                                                        height="25px"></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
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
    <script src="https://cdn.ckeditor.com/4.20.1/full-all/ckeditor.js"></script>
    <script src="{{ asset('vendor/laravel-filemanager/js/stand-alone-button.js') }}"></script>
    <script src="{{ asset('admin/dist/js/custom-ckeditor.js') }}"></script>

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

        function exportBrand(_this) {
            let _url = $(_this).data('href');
            window.location.href = _url;
        }
    </script>
@endpush
