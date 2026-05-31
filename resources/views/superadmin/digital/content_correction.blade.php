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
                    <li class="breadcrumb-item">
                        <a href="{{URL::to('/')}}/superadmin/digitalmarketing">
                            <img src="{{URL::to('/')}}/img/icons/box.png"> <br> <span class="nav-link-text ms-1">Dashboard</span>
                        </a>
                    </li>


                    <li class="breadcrumb-item">
                        <a href="{{ route('superadmin.required.content') }}">
                            <img src="{{URL::to('/')}}/img/icons/box.png"> <br> <span
                                class="nav-link-text ms-1">Required Information</span>
                        </a>
                    </li>


                    {{-- <li class="breadcrumb-item" aria-current="page">
                        <a href="{{URL::to('/')}}/superadmin/boosting">
                            <img src="{{URL::to('/')}}/img/icons/categories.png" > <br><span class="nav-link-text ms-1">Boosting</span>
                        </a>
                    </li> --}}
                    <li class="breadcrumb-item" aria-current="page">
                        <a href="{{URL::to('/')}}/superadmin/content">
                            <img src="{{URL::to('/')}}/img/icons/categories.png" > <br><span class="nav-link-text ms-1">Content</span>
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <a href="{{URL::to('/')}}/superadmin/content_correction">
                            <img src="{{URL::to('/')}}/img/icons/categories.png" > <br><span class="nav-link-text ms-1">Content Correction</span>
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
            <h4>Content Correction Chat List</h4>
        </div>
        <div class="col-md-6">
            <ul>
                <li style="padding:0px;border:0px;"><a  style="display:block;border-radius:0px !important" class="btn btn-secondary">Print</a></li>
            </ul>
        </div>
    </div>
    <div class="row mt-5 productlist">
        <div class="col-md-6 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h4>List</h4>
                </div>
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th>Store</th>
                                <th>Token</th>
                                <th>New Message</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($lists as $key=>$list)
                            <tr>
                                <td>{{$key+1}}</td>
                                <td>{{$list->store_id}}</td>
                                <td>{{$list->token}}</td>
                                <?php
                                $tokens=DB::table('trickets')->where('token',$list->token)->where('seen',"0")->count();
                                ?>
                                <td>{{$tokens}}</td>
                                <td>
                                    <a href="{{route('superadmin.customizerequest.startchat',$list->token)}}" class="btn btn-primary">Chat</a>
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
</main>
@endsection
@push('scripts')

<script>
 $('#submit').on('click',function(){
     var form = $(this).parents('form');
     var note=$('#action').val();
     if(note != 'select'){
        swal.fire({
          title: 'Are you sure?',
          text: "You want to "+note+" this selected item",
          type: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Yes, '+note+' it!',
          cancelButtonText: 'No, cancel!',
          reverseButtons: true
        }).then((result) => {
          if (result.value) {
              console.log(form);
            $('#submitform').submit();
            form.submit();
          } else if (
            result.dismiss === Swal.DismissReason.cancel
          ){
            swal.fire(
              'Cancelled',
              ''+note+' Cancel :)',
              'error'
            )
          }
        })
     }
 })
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
