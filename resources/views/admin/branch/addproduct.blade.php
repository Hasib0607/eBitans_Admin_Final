@extends('admin.layouts.main')
@section('content')
    <style>
        .card {
            border: 1px solid rgba(222, 226, 230, 0.7);
        }

        .card .card-body {
            font-family: "Roboto", Helvetica, Arial, sans-serif;
            padding: .5rem 1.5rem 1.5rem 1.5rem;
        }

        .card .card-header {
            padding: .5rem 1.5rem .5rem 1.5rem;
            border-bottom: 1px solid rgba(222, 226, 230, 0.7);
        }

        .size {
            list-style-type: none;

        }

        .size li {
            float: left;
        }

        .table td,
        .table th {
            white-space: nowrap;
            text-align: center !important;
        }
    </style>
    <div class="modal fade" id="exampleModalScrollable" tabindex="-1" role="dialog"
         aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable" role="document">
            <form action="{{ route('admin.savestafftobranch') }}" method="post">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalScrollableTitle">Staff List</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-stripped">
                            <thead>
                            <tr>
                                <th><input type="checkbox" name="ids" id="checkedAll"></th>
                                <th>Name</th>
                                <th>Phone</th>
                            </tr>
                            </thead>
                            <tbody>
                            <input type="hidden" name="text2" id="selectids">
                            <input type="hidden" name="branchid" value="{{ $branch->id }}">
                            @if (count($staffs) > 0)
                                @foreach ($staffs as $staff)
                                    <tr>
                                        <td><input type="checkbox" name="selectedid" value="{{ $staff->id }}"
                                                   id="id" class="checkSingle"></td>
                                        <td>{{ $staff->name }}</td>
                                        <td>{{ $staff->phone }}</td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="exampleModalScrollable1" tabindex="-1" role="dialog"
         aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable" role="document">
            <form action="{{ route('admin.saveproducttobranch') }}" method="post">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalScrollableTitle">Product List</h5>
                        <!--<button type="button" class="close" data-dismiss="modal" aria-label="Close">-->
                        <!--  <span aria-hidden="true">&times;</span>-->
                        <!--</button>-->
                        <button type="button" class="close" onclick="hidemodal()">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="search" style="padding-top:30px;padding-bottom:30px;">
                            <input type="text" name="search" id="taskfilter" class="form-control" style="width:50%">
                        </div>
                        <div class="table-responsive">
                            <table class="table table-stripped" id="taskfilterresult">
                                <thead>
                                <tr>
                                    <th><input type="checkbox" name="ids" id="checkedAll1"></th>
                                    <th>Name</th>
                                    <th>SKU</th>
                                    <th>Barcode</th>
                                </tr>
                                </thead>
                                <tbody>
                                <input type="hidden" name="text21" id="selectids1">
                                <input type="hidden" name="branchid" value="{{ $branch->id }}">
                                @if (count($products) > 0)
                                    @foreach ($products as $product)
                                        <tr>
                                            <td><input type="checkbox" name="selectedid1[]" value="{{ $product->id }}"
                                                       id="id" class="checkSingle1"></td>
                                            <td>{{ Str::of($product->name)->limit(20) }}</td>
                                            <td>{{ $product->SKU }}</td>
                                            <td class="d-none">{{ $product->barcode }}</td>
                                            <td>
                                                @if (isset($product->barcode) && $product->barcode != '')
                                                    {!! DNS1D::getBarcodeHTML(ucwords($product->barcode), 'C128', 1.4, 22) !!}
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="exampleModalScrollable2" tabindex="-1" role="dialog"
         aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable" role="document">
            <form action="{{ route('admin.product.transfer') }}" method="post" id="productTransferForm">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalScrollableTitle">Transfer Product</h5>
                        <button type="button" class="close" onclick="toggleTransferModal()" style="border: none;">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        @php
                            $userData = getUserData();
                            $store_id = $userData['store_id'];

                            $branches = DB::table('branches')
                                ->where('store_id', $store_id)
                                ->where('status', 'active')
                                ->get();
                        @endphp
                        <div class="row">
                            <input type="hidden" id="product_id" name="product_id" value="">
                            <input type="hidden" id="branch_product_id" name="branch_product_id" value="">
                            <div class="col-sm-12 mb-3">
                                <label class="form-label">
                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                        শাখা (থেকে)
                                    @else
                                        Branch (From)
                                    @endif
                                </label>
                                <select class="form-select" name="fromBranch" id="fromBranch" disabled readonly>
                                    @foreach ($branches as $item)
                                        @isset($item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                                <p class="text-danger" id="fromBranchError" role="alert"></p>
                            </div>

                            <div class="col-sm-12 mb-3">
                                <label class="form-label">
                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                        বর্তমান পরিমাণ
                                    @else
                                        Current Quantity
                                    @endif
                                </label>
                                <input placeholder="" type="number"
                                       min="0" class="form-control"
                                       name="oldProductQty"
                                       value=""
                                       id="oldProductQty" disabled readonly>
                                <p class="text-danger" id="quantityError" role="alert"></p>
                            </div>

                            <div class="col-sm-12 mb-3">
                                <label class="form-label">
                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                        শাখা (পর্যন্ত)
                                    @else
                                        Branch (To)
                                    @endif
                                    <span class="req">*</span>
                                </label>
                                <select class="form-select" name="toBranch" id="toBranch">
                                    @foreach ($branches as $item)
                                        @isset($item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                                <p class="text-danger" id="toBranchError" role="alert"></p>
                            </div>

                            <div class="col-sm-12 mb-3">
                                <label class="form-label">
                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                        পরিমাণ
                                    @else
                                        Quantity
                                    @endif
                                    <span class="req">*</span>
                                </label>
                                <input placeholder="" type="number"
                                       min="0" class="form-control"
                                       name="quantity"
                                       value="1"
                                       id="productQty">
                                <p class="text-danger" id="quantityError" role="alert"></p>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" onclick="productTransferHandler(event)">Save
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <main class="main-content position-relative h-100 border-radius-lg">
        <div class="container-fluid navbars"
             style="background-image: linear-gradient(53deg, #42424a 0%, #191919 100%);">
            <div class="row new">
                <div class="col-md-12">
                    <nav style="--bs-breadcrumb-divider: '';" aria-label="breadcrumb">
                        <ol class="breadcrumb" style="background-color:transparent;color:#fff">
                            <li class="breadcrumb-item active">
                                <a href="{{ route('admin.branch.index') }}">
                                    <img src="{{ URL::to('/') }}/img/cubes.png"> <br> Branch
                                </a>
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <section class="container content-main">
            <div class="row">

                <div class="row">
                    <div class="col-lg-9 mt-4 mb-4">
                        <div class="content-header row">
                            <div class="col-md-6">
                                <h2 class="content-title">Add Product</h2>
                            </div>

                            <div class="col-md-6" style="text-align:right">
                                <!-- <button class="btn btn-light rounded font-sm mr-5 text-body hover-up">Save to draft</button> -->
                                <!-- <button class="btn btn-info rounded font-sm hover-up">Publich</button> -->
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-12">
                                <form action="{{ route('admin.updateinventoryquantity') }}" method="post">
                                    @csrf
                                    <div class="card">
                                        <div class="card-header">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <h5>Inventory</h5>
                                                <div class="search">
                                                    <input type="text" name="search" id="productFilter"
                                                           class="form-control" placeholder="Search">
                                                </div>
                                                <button type="button" class="btn btn-primary" onclick="addproduct(event)">Add Product
                                                </button>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table table-stripped" id="productFilterResult">
                                                    <thead>
                                                    <tr>
                                                        <th>SL</th>
                                                        <th>Product Name</th>
                                                        <th>SKU</th>
                                                        <th>Quantity</th>
                                                        <th>Barcode</th>
                                                        <th>Remove</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php

                                                    $i = 1;
                                                    ?>
                                                    @if (count($bps) > 0)
                                                        @foreach ($bps as $bp)
                                                                <?php
                                                                $product = DB::table('products')
                                                                    ->where('id', $bp->product_id)
                                                                    ->first();
                                                                ?>
                                                            @if (isset($product))
                                                                <tr>
                                                                    <input type="hidden" value="{{ $bp->id }}"
                                                                           name="bid[]">
                                                                    <td>{{ $i++ }}</td>
                                                                    <td>{{ Str::of($product->name)->limit(20) }}</td>
                                                                    <td>{{ $product->SKU }}</td>
                                                                    <td><input type="number"
                                                                               value="{{ $bp->quantity ?? $product->quantity }}"
                                                                               name="qty[]"></td>
                                                                    <td class="d-none">
                                                                        {{ $product->barcode }}
                                                                    </td>
                                                                    <td>
                                                                        @if (isset($product->barcode) && $product->barcode != '')
                                                                            {!! DNS1D::getBarcodeHTML(ucwords($product->barcode), 'C128', 1.4, 22) !!}
                                                                        @endif
                                                                    </td>
                                                                    <td>
                                                                        <a href="javascript:void(0)"
                                                                           onclick="openTransferProduct(JSON.stringify({{$bp}}))"
                                                                           class="btn btn-secondary">
                                                                            <i class="fa fa-exchange"
                                                                               aria-hidden="true"></i>
                                                                        </a>
                                                                        <a href="{{ route('admin.deleteproductfrombranch', $bp) }}"
                                                                           class="btn btn-danger sm">
                                                                            <img src="{{ asset('img/delete.png') }}"
                                                                                 width="25px" height="25px">
                                                                        </a>
                                                                    </td>
                                                                </tr>
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="card-footer">
                                            <button type="submit" class="btn btn-primary">Update Inventory</button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </section>
        </div>
    </main>
@endsection

@push('scripts')
    <script>
        function addproduct(event) {
            if (event) {
                event.preventDefault();
            }

            $('#exampleModalScrollable1').modal('toggle');
        }

        function addstaff() {
            $('#exampleModalScrollable').modal('toggle');
        }

        function hidemodal() {
            $('#exampleModalScrollable1').modal('hide');
        }

        const productTransferHandler = (e) => {
            e.preventDefault();

            let fromBranch = parseInt($("#fromBranch").val());
            let toBranch = parseInt($("#toBranch").val());
            let oldProductQty = parseFloat($("#oldProductQty").val());
            let productQty = parseFloat($("#productQty").val());

            if (fromBranch === toBranch) {
                Swal.fire({
                    title: 'Warning',
                    text: "Old Branch and Transfer Branch Can not be same!",
                    icon: 'warning',
                });
                return false;
            } else if (oldProductQty < productQty) {
                Swal.fire({
                    title: 'Warning',
                    text: "Transfer Quantity Can not be Greater Than Actual Quantity!",
                    icon: 'warning',
                });
                return false;
            } else if (productQty <= 0) {
                Swal.fire({
                    title: 'Warning',
                    text: "Transfer Quantity Can not be Zero!",
                    icon: 'warning',
                });
                return false;
            }

            $("#productTransferForm").submit();
        }

        function openTransferProduct(data) {
            const product = JSON.parse(data);
            $("#branch_product_id").val(product?.id);
            $("#product_id").val(product?.product_id);
            $("#fromBranch").val(product?.branch_id);
            $("#oldProductQty").val(product?.quantity);

            toggleTransferModal();
        }

        function toggleTransferModal() {
            $('#exampleModalScrollable2').modal('toggle');
        }

        jQuery('select[name="category"]').on('change', function () {
            debugger;
            var val = $(this).val();
            console.log(val);
            $('#subcategory').empty();
            var catid = $('select[name="category"]').val();
            $.get('/getsubcat', {
                catid: catid
            }, function (data) {
                console.log(data);
                for (var i = 0; i < data.length; i++) {
                    $('#subcategory').append(
                        '<option value="">select</option><option value="' + data[i].id + '">' + data[i]
                            .name + '</option>'
                    );
                }
            });
        });
    </script>
    <script>
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
    </script>
    <script>
        $(document).ready(function () {
            $("#checkedAll1").change(function () {
                debugger;
                if (this.checked) {
                    $(".checkSingle1").each(function () {
                        debugger;
                        this.checked = true;
                        var valuesArray = $('input[name="selectedid1"]:checked').map(function () {
                            return this.value;
                        }).get().join(",");
                        $("#selectids1").val(valuesArray);
                        $("#selectdelids1").val(valuesArray);
                    });
                } else {
                    $(".checkSingle1").each(function () {
                        this.checked = false;
                    });
                    var valuesArray = '';
                    $("#selectids1").val(valuesArray);
                    $("#selectdelids1").val(valuesArray);
                }
            });
            $(".checkSingle1").click(function () {
                if ($(this).is(":checked")) {
                    var isAllChecked = 0;
                    $(".checkSingle1").each(function () {
                        if (!this.checked)
                            isAllChecked = 1;
                        var valuesArray = $('input[name="selectedid1"]:checked').map(function () {
                            return this.value;
                        }).get().join(",");
                        $("#selectids1").val(valuesArray);
                        $("#selectdelids1").val(valuesArray);
                    });
                    if (isAllChecked == 0) {
                        $("#checkedAll1").prop("checked", true);
                    }
                } else {
                    $("#checkedAll1").prop("checked", false);
                    var valuesArray = $('input[name="selectedid1"]:checked').map(function () {
                        return this.value;
                    }).get().join(",");
                    $("#selectids1").val(valuesArray);
                    $("#selectdelids1").val(valuesArray);
                }
            });
        });
    </script>
    <script>
        $(document).ready(function () {
            $("#taskfilter").on("keyup", function () {
                var value = $(this).val().toLowerCase();
                $("#taskfilterresult tbody tr").filter(function () {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });

            $("#productFilter").on("keyup", function () {
                var value = $(this).val().toLowerCase();
                $("#productFilterResult tbody tr").filter(function () {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });
        });

        function exportTasks(_this) {
            let _url = $(_this).data('href');
            window.location.href = _url;
        }
    </script>
@endpush
