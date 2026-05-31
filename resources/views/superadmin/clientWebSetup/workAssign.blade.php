@extends('admin.layouts.main')
@push('styles')
<style>
    .fade:not(.show) {
      opacity: 0 !important;
    }

        .modal-body {
            border: 1px solid rgba(222, 226, 230, 0.7);
        }

        .modal-body {
            font-family: "Roboto", Helvetica, Arial, sans-serif;
            padding: .5rem 1.5rem 1.5rem 1.5rem;
        }

        .modal-body {
            padding: .5rem 1.5rem .5rem 1.5rem;
            border-bottom: 1px solid rgba(222, 226, 230, 0.7);
        }

        .size {
            list-style-type: none;

        }

        .size li {
            float: left;
        }

        .select2{
            width: 100%!important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #444;
            line-height: 35px !important;
        }

        .select2-container .select2-selection--single {
            height: 39px !important;
        }

</style>
@endpush
@section('content')
<!-- Modal -->



<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
<div class="container-fluid navbars" style="background-image: linear-gradient(53deg, #42424a 0%, #191919 100%);">
    <div class="row">
        <div class="col-md-12">
            <nav style="--bs-breadcrumb-divider: '';" aria-label="breadcrumb">
                <ol class="breadcrumb" style="background-color:transparent;color:#fff">
                    @if (Auth::user()->type == 'superadmin')
                    <li class="breadcrumb-item">
                        <a href="{{route('staff.workAssign')}}">
                            <img src="{{URL::to('/')}}/img/cubes.png"> <br> Work Assign
                        </a>
                    </li>
                    @endif
                    <li class="breadcrumb-item">
                        <a href="{{route('staff.webSetUp')}}">
                            <img src="{{URL::to('/')}}/img/cubes.png"> <br>Website Setup
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
            <h4>Website Setup</h4>
        </div>
        <div class="col-md-6">
            <ul>
                <!--<li style="padding:0px;border:0px;"><a href="javascript:void(0)" class="btn btn-primary" style="display:block;border-radius:0px !important">Create New</a></li>-->
                <!--<li style="padding:0px;border:0px;"><a href="javascript:void(0)" style="display:block;border-radius:0px !important" class="btn btn-secondary">Export</a></li>-->
            </ul>
        </div>
    </div>
    <div class="row mt-5 productlist">
        <div class="col-12">
        <div class="card">
            <div class="card-body">
            @if (Session::has('success_message'))
                    <div class="alert alert-success">{{Session::get('success_message')}}</div>
                @endif
                <div class="table-responsive">
                    <table class="table" id="taskfilterresult" width="100%">
                        <thead>
                            <tr>
                                {{-- <th><input type="checkbox"></th> --}}
                                <th>Name</th>
                                <th>Phone</th>
                                <th>Work</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!--@if(isset($staff) && count($staff)>0)-->
                            @foreach($staff as $key=>$data)
                            <tr style="border-bottom:1px solid rgba(0, 0, 0, 0.125)">
                                <td>
                                    <strong>{{$data->name ?? ""}}</strong>
                                    <br>
                                    <small>{{$data->username ?? ""}}</small>
                                </td>
                                <td>{{ $data->phone?? ""}}</td>
                                
                                
                                
                                
                                
                                
                                <td>
                                    @foreach ($data->getWork as $item)
                                        <span style="background-color: #009688; color:white" class="px-2 py-1">{{ $item->getStore->name ?? "" }}</span>
                                    @endforeach

                                </td>
                                <td>
                                     <button onclick="LoginModal({{ $data->uid }})" class="btn btn-info">Assign Work</button>
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




{{-- modal   --}}
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <form method="post" action="{{route('staff.workAssign.store')}}" enctype="multipart/form-data">
          @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel"> Work Assign </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">

            <input type="hidden" id="staff" name="staff" value="">

            <div class="form-group pb-3">
                <label for="store" >Store Name</label>
                <select class="form-control js-example-basic-multiple" name="store[]" aria-placeholder="Select Store Name"
                    id="store" multiple="multiple">
                    @foreach ($setup as $item)
                        <option value="{{ $item->id }}">{{ $item->getStore->name??'------' }}</option>
                    @endforeach
                </select>
                @error('store')
                    <p class="text-danger" role="alert">{{ $message }}</p>
                @enderror
            </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-info">Login Now</button>
        </div>
      </div>
      </form>
    </div>
  </div>
  {{-- modal end  --}}
</main>
@endsection
@push('scripts')

<script>
    function LoginModal(id) {
        $('#staff').val(id);
        $('#exampleModal').modal('toggle');
    }
</script>

<script>
    $(document).ready(function(){
      $("#taskfilter").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("#taskfilterresult tbody tr").filter(function() {
          $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
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
        $('.js-example-basic-multiple').select2();
    });
</script>
@endpush
