@extends('admin.layouts.main')
@section('content')
<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
<div class="container-fluid navbars" style="background-image: linear-gradient(53deg, #42424a 0%, #191919 100%);">
    <div class="row">
        <div class="col-md-12">
            <nav style="--bs-breadcrumb-divider: '';" aria-label="breadcrumb">
                <ol class="breadcrumb" style="background-color:transparent;color:#fff">
                    <li class="breadcrumb-item active">
                        <a href="#">
                            <img src="{{URL::to('/')}}/img/cubes.png"> <br> Branch Delete Request
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
            <h4>Branch Delete Request List</h4>
        </div>
        <div class="col-md-6">
            <ul>
                <li class="active"><a href="#">Create New</a></li>
                <li><a href="">Import</a></li>
                <li><a data-href="/tasks" onclick="exportTasks(event.target);">Export</a></li>
            </ul>
        </div>
    </div>
    <div class="row mt-5 productlist">
        <div class="col-12">
        <!--<div class="alert alert-info" role="alert">-->
        <!--<span style="color:#fff">Total Branch add 0/5</span>-->
    <!--</div>-->
        </div>
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
                    <div class="col-md-5">

                    </div>
                    <div class="col-md-2">
                        <input type="date" name="date" class="form-control">
                    </div>
                    <div class="col-md-2">
                        <select class="form-select">
                            <option>Select</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="card-body">
            @if (Session::has('success_message'))
                    <div class="alert alert-success">{{Session::get('success_message')}}</div>
                @endif
                <div class="table-responsive">
                    <table class="table" width="100%">
                        <thead>
                            <tr>
                                <th width="4%"><input type="checkbox"></th>
                                <th width="5%">Branch Name</th>
                                <th width="15%">Email</th>
                                <th width="10%">Phone</th>
                                <th width="10%">Company Name</th>
                                <th width="15%">Address</th>
                                <th width="10%">Status</th>
                                <th width="15%">Date</th>
                                <th width="11%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($branchs as $branch)
                            <tr style="border-bottom:1px solid rgba(0, 0, 0, 0.125)">
                                <td><input type="checkbox" name="id" value="{{$branch->id}}"></td>
                                <td>{{$branch->name}}</td>
                                <td>{{$branch->email}}</td>
                                <td>{{$branch->phone}}</td>
                                <?php
                                $customer=DB::table('customers')->where('id',$branch->customer_id)->first();
                                ?>
                                <td>{{$customer->company_name ?? ""}}</td>
                                <td>{{$branch->address}}</td>
                                <td>{{$branch->status}}</td>
                                <td>{{$branch->created_at}}</td>
                                <td>
                                    <a href="{{route('restoredeletebranch',$branch->id)}}" class="btn btn-secondary">Restore</a>
                                    &nbsp;&nbsp;&nbsp;
                                    <a href="{{route('superadmindeletebranch',$branch->id)}}" class="btn btn-danger">Delete</a>
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