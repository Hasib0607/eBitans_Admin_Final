@extends('admin.layouts.main')
@section('content')
<style>
    .card{
        border:1px solid rgba(222, 226, 230, 0.7);
    }
    .card .card-body {
    font-family: "Roboto", Helvetica, Arial, sans-serif;
    padding: .5rem 1.5rem 1.5rem 1.5rem;
}
.card .card-header{
    padding: .5rem 1.5rem .5rem 1.5rem;
    border-bottom:1px solid rgba(222, 226, 230, 0.7);
}
.size{
    list-style-type:none;

}
.size li{
    float:left;
}
.table td, .table th {
  white-space: nowrap;
  text-align: center !important;

</style>
<div class="modal fade" id="exampleModalScrollable" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable" role="document">
      <form action="{{route('admin.savestafftobranch')}}" method="post">
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
                <input type="hidden" name="branchid" value="{{$branch->id}}">
                @if(count($staffs)>0)
                @foreach($staffs as $staff)
                <tr>
                    <td><input type="checkbox" name="selectedid" value="{{$staff->id}}" id="id" class="checkSingle"></td>
                    <td>{{$staff->name}}</td>
                    <td>{{$staff->phone}}</td>
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

<div class="modal fade" id="exampleModalScrollable1" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable" role="document">
      <form action="{{route('admin.saveproducttobranch')}}" method="post">
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
                </tr>
            </thead>
            <tbody>
                <input type="hidden" name="text21" id="selectids1">
                <input type="hidden" name="branchid" value="{{$branch->id}}">
                @if(count($products)>0)
                @foreach($products as $product)
                <tr>
                    <td><input type="checkbox" name="selectedid1[]" value="{{$product->id}}" id="id" class="checkSingle1"></td>
                    <td>{{Str::of($product->name)->limit(20)}}</td>
                    <td>{{$product->SKU}}</td>
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
<main class="main-content position-relative h-100 border-radius-lg">
<div class="container-fluid navbars" style="background-image: linear-gradient(53deg, #42424a 0%, #191919 100%);">
    <div class="row new">
        <div class="col-md-12">
            <nav style="--bs-breadcrumb-divider: '';" aria-label="breadcrumb">
                <ol class="breadcrumb" style="background-color:transparent;color:#fff">
                    <li class="breadcrumb-item active">
                        <a href="{{route('admin.branch.index')}}">
                            <img src="{{URL::to('/')}}/img/cubes.png"> <br> Branch
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
                        <div class="col-md-8">
                            <form action="{{route('admin.updateinventoryquantity')}}" method="post">
                                @csrf
                            <div class="card">
                                <div class="card-header">
                                    Inventory<span class="" onclick="addproduct()" style="text-align: end;right: 10px;position: absolute;cursor: pointer;font-weight: bold;color: blueviolet;">Add Product</span>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                    <table class="table table-stripped">
                                        <thead>
                                            <tr>
                                                <th>SL</th>
                                                <th>Product Name</th>
                                                <th>SKU</th>
                                                <th>Quantity</th>
                                                <th>Remove</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            
                                            $i=1;
                                            ?>
                                            @if(count($bps)>0)
                                            @foreach($bps as $bp)
                                            <?php
                                            $product=DB::table('products')->where('id',$bp->product_id)->first();
                                            ?>
                                            @if(isset($product))
                                            <tr>
                                                <input type="hidden" value="{{$bp->id}}" name="bid[]">
                                                <td>{{$i++}}</td>
                                                <td>{{Str::of($product->name)->limit(20)}}</td>
                                                <td>{{$product->SKU}}</td>
                                                <td><input type="number" value="{{$bp->quantity ?? $product->quantity}}" name="qty[]" ></td>
                                                <td><a href="{{route('admin.deleteproductfrombranch',$bp)}}" class="btn btn-danger">X</a></td>
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
    function addproduct(){
         $('#exampleModalScrollable1').modal('toggle');
    }
    function addstaff(){
        $('#exampleModalScrollable').modal('toggle');
    }
    function hidemodal(){
        $('#exampleModalScrollable1').modal('hide');
    }
jQuery('select[name="category"]').on('change',function(){
        debugger;
        var val = $(this).val();
        console.log(val);
        $('#subcategory').empty();
        var catid=$('select[name="category"]').val();
        $.get('/getsubcat',{catid:catid},function(data){
            console.log(data);
            for (var i = 0; i <data.length; i++) {
            $('#subcategory').append(
                
                '<option value="">select</option><option value="'+data[i].id+'">'+data[i].name+'</option>'
                );
            }
        });
    });
</script>    
<script>
$(document).ready(function() {
    $("#checkedAll").change(function() {
        debugger;
        if (this.checked) {
            $(".checkSingle").each(function() {
                debugger;
                this.checked=true;
                var valuesArray = $('input[name="selectedid"]:checked').map(function () {  
                return this.value;
                }).get().join(",");
                $("#selectids").val(valuesArray);
                $("#selectdelids").val(valuesArray);
            });
        } else {
            $(".checkSingle").each(function() {
                this.checked=false;
            });
            var valuesArray ='';
            $("#selectids").val(valuesArray);
            $("#selectdelids").val(valuesArray);
        }
    });
    $(".checkSingle").click(function () {
        if ($(this).is(":checked")) {
            var isAllChecked = 0;
            $(".checkSingle").each(function() {
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
        }
        else {
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
$(document).ready(function() {
    $("#checkedAll1").change(function() {
        debugger;
        if (this.checked) {
            $(".checkSingle1").each(function() {
                debugger;
                this.checked=true;
                var valuesArray = $('input[name="selectedid1"]:checked').map(function () {  
                return this.value;
                }).get().join(",");
                $("#selectids1").val(valuesArray);
                $("#selectdelids1").val(valuesArray);
            });
        } else {
            $(".checkSingle1").each(function() {
                this.checked=false;
            });
            var valuesArray ='';
            $("#selectids1").val(valuesArray);
            $("#selectdelids1").val(valuesArray);
        }
    });
    $(".checkSingle1").click(function () {
        if ($(this).is(":checked")) {
            var isAllChecked = 0;
            $(".checkSingle1").each(function() {
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
        }
        else {
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
    $(document).ready(function(){
      $("#taskfilter").on("keyup", function() {
          debugger;
        var value = $(this).val().toLowerCase();
        debugger;
        $("#taskfilterresult tbody tr").filter(function() {
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
</script>
@endpush
