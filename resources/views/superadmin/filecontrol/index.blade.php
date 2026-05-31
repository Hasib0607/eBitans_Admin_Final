@extends('admin.layouts.main')
@push('styles')
<link href="https://unpkg.com/filepond/dist/filepond.css" rel="stylesheet">
<style>
    .my-pond{
        width:100% !important;
    }
</style>
@endpush
@section('content')
<style>
    .input-group-text{

    }
    .custom-scroll {
         height: 69vh !important;
         /*overflow-y: scroll; !* Ensure vertical scrolling *!*/
         /*overflow-y: hidden; !* Hide vertical scrolling *!*/
         /*overflow-x: scroll; !* Enable horizontal scrolling *!*/
         /*white-space: nowrap;*/
         padding: 10px;
         box-sizing: border-box;
         outline: none;            /* Adjust height as needed */
         overflow-y: auto;
         scroll-behavior: initial;
     }
    /* Custom Scrollbar for WebKit Browsers (e.g., Chrome, Safari) */
    .custom-scroll::-webkit-scrollbar {
        width: 8px; /* Vertical scrollbar width */
    }

    .custom-scroll::-webkit-scrollbar-track {
        background: #f1d0c9;
        border-radius: 10px;
    }

    .custom-scroll::-webkit-scrollbar-thumb {
        background: #dd8d7c;
        border-radius: 10px;
        border: 2px solid transparent;
        background-clip: padding-box;
    }

    .custom-scroll::-webkit-scrollbar-thumb:hover {
        background: #f1593a;
    }
</style>
<!--Modal-->
<div class="modal fade" id="exampleModal2" tabindex="-1" aria-labelledby="exampleModalLabel2" aria-hidden="true">
  <div class="modal-dialog">
    <form action="{{route('fileuploads')}}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel2">Upload File</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">

            <div class="row">
                <div class="col-md-12">
                        <label class="form-label">UiFile Version Name</label>
                        <input type="text" placeholder="Type here" class="form-control" name="version_name" onfocus="focused(this)" onfocusout="defocused(this)">
                </div>

                <div class="col-md-6 mt-3">
                        <label class="form-label">Build JS</label>
                        <input type="text" placeholder="Type here" class="form-control" name="build_js" onfocus="focused(this)" onfocusout="defocused(this)">
                </div>

                <div class="col-md-6 mt-3">
                    <label class="form-label">Build CSS</label>
                    <input type="text" placeholder="Type here" class="form-control" name="build_css" onfocus="focused(this)" onfocusout="defocused(this)">
                </div>

                <div class="col-md-12 mt-3">
                    <label class="form-label">Zip File</label>
                    <input type="file" placeholder="Type here" class="form-control" name="file_name" onfocus="focused(this)" onfocusout="defocused(this)">
                </div>
            </div>

            {{-- <div class="mb-4">
                <label for="basic-url" class="form-label">File</label>
                <div class="input-group mb-3">
                    <input type="file" class="my-pond" name="avatar" id="avatarOld" value=""/>
                </div>
            </div> --}}


          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Submit</button>
          </div>
        </div>
    </form>
  </div>
</div>
<!--Modal-->
<div class="modal fade" id="exampleModal3" tabindex="-1" aria-labelledby="exampleModalLabel3" aria-hidden="true">
  <div class="modal-dialog">
    <form action="{{route('copyfile')}}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel3">Upload File</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">

            <label for="basic-url" class="form-label">Domain</label>
            <div class="input-group mb-3">
            <input type="text" class="form-control" name="domain" readonly/>
            </div>
            <label for="basic-url" class="form-label">File</label>
            <div class="input-group mb-3">
            <select class="form-control" name="file">
                <option value="0">Select</option>
                @foreach($files as $file)
                    <option value="{{$file->id}}">{{$file->file_name}}</option>
                @endforeach
            </select>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Submit</button>
          </div>
        </div>
    </form>
  </div>
</div>
<!--Modal-->
<div class="modal fade" id="exampleModal4" tabindex="-1" aria-labelledby="exampleModalLabel4" aria-hidden="true">
  <div class="modal-dialog">
    <form action="{{route('copyfilemultiple')}}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel4">Upload File</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>

          <div class="modal-body">
            <label for="basic-url" class="form-label">Domain</label>
            <div class="input-group mb-3">
                 <input type="text" class="form-control" name="domain" id="domainsss" readonly/>
            </div>
            <label for="basic-url" class="form-label">File</label>
            <div class="input-group mb-3">
            <select class="form-control" name="file">
                <option value="0">Select</option>
                @foreach($files as $file)
                    <option value="{{$file->id}}">{{ $file->file_name }}</option>
                @endforeach
            </select>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Submit</button>
          </div>
        </div>
    </form>
  </div>
</div>
<!-- Modal -->
<div class="modal fade" id="exampleModal1" tabindex="-1" aria-labelledby="exampleModalLabel1" aria-hidden="true">
  <div class="modal-dialog">
    <form action="{{route('admin.changewebmailpassword')}}" method="post">
        @csrf
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel1">Change Password</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <label for="basic-url" class="form-label">Email</label>
            <div class="input-group mb-3">
            <input type="text" class="form-control" name="email" placeholder="Email's username" aria-label="Recipient's username" aria-describedby="basic-addon2" autocomplete="off" id="recipient-name" readonly>
            </div>

            <label for="basic-url" class="form-label">Password</label>
            <div class="input-group mb-3">
            <input type="password" class="form-control" name="password" id="basic-url" aria-describedby="basic-addon3">
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Submit</button>
          </div>
        </div>
    </form>
  </div>
</div>
<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
<div class="container-fluid navbars" style="background-image: linear-gradient(53deg, #42424a 0%, #191919 100%);">
    <div class="row new">
        <div class="col-md-12">
            <nav style="--bs-breadcrumb-divider: '';" aria-label="breadcrumb">
                <ol class="breadcrumb" style="background-color:transparent;color:#fff">
                    <li class="breadcrumb-item active">
                        <a href="{{route('filecontrol')}}">
                            <img src="{{URL::to('/')}}/img/icons/rating.png"> <br> <span class="nav-link-text ms-1">File Control</span>
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
            <h4>All File </h4>
        </div>
        <div class="col-md-6">
            <ul>
                <li style="padding:0px;border:0px;"><a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal" style="display:block;border-radius:0px !important">Create Email</a></li>
                <li style="padding:0px;border:0px;"><a data-href="/customerexport" onclick="exportTasks(event.target);" style="display:block;border-radius:0px !important" class="btn btn-secondary"> Excel </a></li>
            </ul>
        </div>
    </div>
    <div class="row mt-5 productlist">
        <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-2">
                    <form id="submitform" method="post" action="{{route('deletedomainsdata')}}">
                        @csrf
                    <input type="hidden" name="text2" id="selectids">
                        <select class="form-control" name="options" id="options">
                            <option value="0">Select </option>

                            <option value="2">Upgrade</option>
                        </select>
                    </form>
                    </div>
                    <div class="col-md-6"></div>
                    <div class="col-md-4">
                        <div class="input-group" >
                            <input type="text" class="form-control" aria-label="Dollar amount (with dot and two decimal places)" id="taskfilter">
                            <span class="input-group-text" style="padding: 0.75rem 11px !important;"><i class="fa fa-search"></i></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body custom-scroll" style="height: 70vh; ">
                @if (Session::has('success_message'))
                    <div class="alert alert-success">{{Session::get('success_message')}}</div>
                @endif
                <div class="table-responsive" id="desktoptable">
                    <table class="table table-striped" id="taskfilterresult" width="100%">
                        <thead>
                            <tr>
                                <th width="4%"><input type="checkbox" name="ids" id="checkedAll"></th>
                                <th width="10%">Domain/Subdomain</th>
                                <th width="16%"> Action </th>
                            </tr>
                        </thead>
                        <tbody class="">

                            <tr style="border-bottom:1px solid rgba(0, 0, 0, 0.125)">
                                <td>
                                    <input type="checkbox" name="selectedid" value="{{ $data->main_domain }}" id="id" class="checkSingle">
                                </td>
                                <td>
                                    {{ $data->main_domain }}
                                </td>

                                <td>
                                    <a href="javascript:void(0)" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal3" class="btn btn-info" data-bs-whatever="{{ $data->main_domain }}">Upgrade</a>
                                </td>
                            </tr>

                            @foreach($data->sub_domains as $do)
                                @if ($do != '*.'.env('SUB_URL'))
                                    @if ($do != "admin.".env('SUB_URL'))
                                        <tr style="border-bottom:1px solid rgba(0, 0, 0, 0.125)">
                                            <td>
                                                <input type="checkbox" name="selectedid" value="{{$do}}" id="id" class="checkSingle">
                                            </td>
                                            <td>
                                                {{$do}}
                                            </td>

                                            <td>
                                                <a href="javascript:void(0)" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal3" class="btn btn-info" data-bs-whatever="{{$do}}">Upgrade</a>
                                            </td>
                                        </tr>
                                    @endif
                                @endif
                            @endforeach
                            @foreach($data->addon_domains as $do)
                                    <tr style="border-bottom:1px solid rgba(0, 0, 0, 0.125)">
                                        <td>
                                            <input type="checkbox" name="selectedid" value="{{$do}}" id="id" class="checkSingle">
                                        </td>
                                        <td>
                                            {{$do}}
                                        </td>

                                        <td>
                                            <a href="javascript:void(0)" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal3" class="btn btn-info" data-bs-whatever="{{$do}}">Upgrade</a>
                                        </td>
                                    </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        </div>
        <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <div class="row">


                    <div class="col-md-8"></div>
                    <div class="col-md-4">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal2">Upload File</button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @if (Session::has('success_message'))
                    <div class="alert alert-success">{{Session::get('success_message')}}</div>
                @endif
                <div class="table-responsive" id="desktoptable">
                    <table class="table table-striped" id="taskfilterresult" width="100%">
                        <thead>
                            <tr>
                                <!--<th width="4%"><input type="checkbox" name="ids" id="checkedAll"></th>-->
                                <th width="10%">File Name</th>
                                <th width="16%"> Action </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($files as $file)
                            <tr style="border-bottom:1px solid rgba(0, 0, 0, 0.125)">
                                <!--<td>-->
                                <!--    <input type="checkbox" name="selectedid" value="{{$file->id}}" id="id" class="checkSingle">-->
                                <!--</td>-->
                                <td>
                                    {{$file->file_name}}
                                </td>
                                <td>
                                    <a href="{{route('deletefile',$file->id)}}" onclick="return confirm('Are you sure you want to delete this item?');"><img src="{{asset('img/delete.png')}}" width="25px" height="25px"></a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                    {!! $files->appends(request()->query())->links() !!}

            </div>
        </div>
        </div>
    </div>
</div>
</main>
@endsection
@push('scripts')
<!-- include FilePond library -->
<script src="https://unpkg.com/filepond/dist/filepond.min.js"></script>

<!-- include FilePond plugins -->
<script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.js"></script>

<!-- include FilePond jQuery adapter -->
<script src="https://unpkg.com/jquery-filepond/filepond.jquery.js"></script>
<script>
const inputElement = document.querySelector('input[id="avatar"]');
const pond=FilePond.create(inputElement);
FilePond.setOptions({
    server:{
        url:'/newupload',
        headers:{
            'X-CSRF_TOKEN':'{{csrf_token()}}'
        }
    }
});
const exampleModal = document.getElementById('exampleModal3')
exampleModal.addEventListener('show.bs.modal', event => {
  const button = event.relatedTarget
  const recipient = button.getAttribute('data-bs-whatever')
  const modalTitle = exampleModal.querySelector('.modal-title')
  const modalBodyInput = exampleModal.querySelector('.modal-body input')

  modalBodyInput.value = recipient
})
// Turn input element into a pond
// $('.my-pond').filepond();



// // Set allowMultiple property to true
// $('.my-pond').filepond('allowMultiple', false);

// // Listen for addfile event
// $('.my-pond').on('FilePond:addfile', function (e) {
//     console.log('file added event', e);
// });
// $('.my-pond').on('FilePond:setOption', function (e) {
//   server:'/uploadfiles'
// });

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
 $('#options').change(function(){
     const value=$(this).val();
     const op=$("#selectids").val();
     if(op==""){
     }else{
         if(value==1){
             const ll= confirm("Are you Sure You want to delete all the file?");
             if(ll==true){
                $('#submitform').submit();
             }
         }
         if(value==2){
            $('#domainsss').val(op);
            const myModal = new bootstrap.Modal(document.getElementById('exampleModal4'), options)
            const modalToggle = document.getElementById('exampleModal4');
            myModal.show(modalToggle)
         }
     }
 })
$(document).ready(function() {
    $("#checkedAll").change(function() {
        if (this.checked) {
            $(".checkSingle").each(function() {
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
