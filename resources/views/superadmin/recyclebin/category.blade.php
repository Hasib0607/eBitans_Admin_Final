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
  transition: transform .2s; /* Animation */
  margin: 0 auto;
}

.zoom:hover {
  transform: scale(7.5); /* (150% zoom - Note: if the zoom is too large, it will go outside of the viewport) */
}
</style>
@endpush
@section('content')
<main class="main-content position-relative  h-100 border-radius-lg">
<div class="container-fluid navbars" style="background-image: linear-gradient(53deg, #42424a 0%, #191919 100%);">
    <div class="row">
        <div class="col-md-12">
            <nav style="--bs-breadcrumb-divider: '';" aria-label="breadcrumb">
                <ol class="breadcrumb" style="background-color:transparent;color:#fff">
                    <li class="breadcrumb-item ">
                        <a href="{{route('superadmin.productrecycle')}}">
                            <img src="{{URL::to('/')}}/img/cubes.png"> <br> Products
                        </a>
                    </li> 
                    <li class="breadcrumb-item active">
                        <a href="{{route('superadmin.categoryrecycle')}}">
                            <img src="{{URL::to('/')}}/img/cubes.png"> <br> Category
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
            <h4>All Category</h4>
        </div>
        <div class="col-md-6">
            <ul>
                <!--<li style="padding:0px;border:0px;"><a href="{{route('admin.addproducts')}}" class="btn btn-primary" style="display:block;border-radius:0px !important">Add Product</a></li>-->
                <!--<li style="padding:0px;border:0px;"><a data-href="/tasks" onclick="exportTasks(event.target);" style="display:block;border-radius:0px !important" class="btn btn-secondary">Export</a></li>-->
            </ul>
        </div>
    </div>
    <div class="row mt-5 productlist">
        <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-1" style="width:3% !important;margin-left:10px;">
                        
                    </div>
                    <div class="col-md-2">
                        <div class="input-group" >
                            <input type="text" class="form-control" aria-label="Dollar amount (with dot and two decimal places)" id="taskfilter">
                            <span class="input-group-text" style="padding: 0.75rem 11px !important;"><i class="fa fa-search"></i></span>
                        </div>
                    </div>
                    <div class="col-md-2">

                    </div>
                
                    <div class="col-md-1 text-end mt-1">
                    </div>
                    <div class="col-md-2">
                    </div>
                    <div class="col-md-2 text-end mt-1">
                    </div>
                    
                    <div class="col-md-1">
                        <form action="{{route('superadmin.deleteallcategory')}}" method="post">
                        @csrf
                        <input type="hidden" name="text3" id="selectids1">
                        <button Type="submit" class="btn btn-primary">Delete</button>
                        </form>
                    </div>
                    <div class="col-md-1">
                        <form action="{{route('superadmin.restoreallcategory')}}" method="post">
                        @csrf
                        <input type="hidden" name="text2" id="selectids">
                        <button Type="submit" class="btn btn-secondary">Restore</button>
                        </form>
                    </div>
               
                </div>
            </div>
            <div class="card-body">
            @if (Session::has('success_message'))
                    <div class="alert alert-success" style="color:#fff">{{Session::get('success_message')}}</div>
                @endif
                <div class="table-responsive">
                    <table class="table" width="100%" id="taskfilterresult">
                        <thead>
                            <tr>
                                <th width="4%"><input type="checkbox"  name="ids" id="checkedAll"></th>
                                <th width="5%">Icon</th>
                                <th width="20%">Banner</th>
                                <th width="20%">Name</th>
                                <th width="10%">Product</th>
                                <th width="5%">Position</th>
                                <th width="10%">Status</th>                                
                                <th width="21%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($categories) && count($categories)>0)
                           @foreach($categories as $cat)
                            <tr style="border-bottom:1px solid rgba(0, 0, 0, 0.125)">
                                <td><input type="checkbox" name="id" value="{{$cat->id}}" id="id" class="checkSingle"></td>
                                <td>
                                    <!--<i class="fa {{$cat->icon}}"></i>-->
                                    <img src="{{URL::to('/')}}/assets/images/icon/{{$cat->icon}}" width="40px">
                                </td>
                                <td><img src="{{URL::to('/')}}/assets/images/category/{{$cat->banner}}" width="60px"></td>
                                <td>{{$cat->name}}</td>
                                <?php
                                $producct=DB::table('products')->where('category',$cat->id)->get();
                                ?>
                                <td>{{count($producct) ?? "0"}}</td>
                                <td>
                                    <input type="hidden" name="idss" id="id" value="{{$cat->id}}">
                                    <input type="number" value="{{$cat->position ?? '0'}}" name="position" id="position" style="width:70%">
                                </td>
                                <td>
                                    <div class="form-check form-switch" style="text-align:center;">
                                        <input class="form-check-input switchstatus" type="checkbox" data-id="{{$cat->id}}" id="flexSwitchCheckChecked"  name="checkstatus" style="margin:0 auto;" @if($cat->status=='active') checked @endif>
                                        <label class="form-check-label" for="flexSwitchCheckChecked"></label>
                                    </div>
                                </td>
                                <td >
                                    <a href="{{URL::to('/')}}/superadmin/categoryrestore/{{$cat->id}}" onclick="return confirm('Are you sure you want to Restore this item?');" class="btn btn-primary">Restore</a>
                                    
                                </td>
                            </tr>
                            @endforeach
                            @endif
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
<script>

</script>
<script>
    $(document).ready(function(){
        $(".switchstatus").on("change",function(){
            $url="/changeprostatus";
            var value=$(this).val();
            console.log(value);
            var id = $(this).data('id');
            console.log(id);
            $.get($url,{value:value,id:id}, function(data){
               console.log(data); 
            });
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
                $("#selectids1").val(valuesArray);
                $("#selectdelids").val(valuesArray);
            });
        } else {
            $(".checkSingle").each(function() {
                this.checked=false;
            });
            var valuesArray ='';
            $("#selectids").val(valuesArray);
            $("#selectids1").val(valuesArray);
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
                $("#selectids1").val(valuesArray);
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
                $("#selectids1").val(valuesArray);
                $("#selectdelids").val(valuesArray);
        }
    });
});
</script>
@endpush