@extends('admin.layouts.main')
@section('content')
<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
<div class="container-fluid navbars" style="background-image: linear-gradient(53deg, #42424a 0%, #191919 100%);">
    <div class="row">
        <div class="col-md-12">
            <nav style="--bs-breadcrumb-divider: '';" aria-label="breadcrumb">
                <ol class="breadcrumb" style="background-color:transparent;color:#fff">
                    <li class="breadcrumb-item">
                        <a href="{{route('admin.staff')}}">
                            <img src="{{URL::to('/')}}/img/icons/employee.png"> <br> <span class="nav-link-text ms-1">@if(Session::has('lang') && Session::get('lang')=='bn') স্টাফ @else Employee @endif</span>
                        </a>
                    </li>
                    <li class="breadcrumb-item active">
                        <a href="{{route('admin.role.permission')}}">
                            <img src="{{URL::to('/')}}/img/icons/permissions.png"> <br> <span class="nav-link-text ms-1">@if(Session::has('lang') && Session::get('lang')=='bn') ভূমিকা এবং অনুমতি @else Role & Permission @endif</span>
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
            <h4>@if(Session::has('lang') && Session::get('lang')=='bn') সব ভূমিকা  @else All Roles @endif</h4>
        </div>
        <div class="col-md-6">
            <ul>
                <!--<li class="active"><a href="{{URL::to('/')}}/category/create">Create New</a></li>-->
                <!--<li><a href="">Import</a></li>-->
                <!--<li><a href="">Export</a></li>-->
            </ul>
        </div>
    </div>
    <div class="row mt-5 productlist">
        <div class="col-lg-4 col-md-12 col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h4>@if(Session::has('lang') && Session::get('lang')=='bn') ভূমিকা সম্পাদনা করুন  @else Edit Roles @endif</h4>
                </div>
                <div class="card-body">
                    <form action="{{route('admin.editrole',$role->id)}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3 row">
                        <label for="staticEmail" class="col-md-3 col-form-label">@if(Session::has('lang') && Session::get('lang')=='bn') নাম @else Name @endif</label>
                        <div class="col-md-8">
                        <input type="text" class="form-control" id="staticEmail" name="name" placeholder="Role Name" value="{{$role->name}}">
                            @error('name')
                                <p class="text-danger">{{$message}}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="position" class="col-md-3 col-form-label"></label>
                        <div class="col-md-8" style="text-align:right">
                            <button type="submit" class="btn btn-info">@if(Session::has('lang') && Session::get('lang')=='bn') আপডেট @else Update @endif </button>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-8 col-md-12 col-sm-12">
        <div class="card">
            <div class="card-header">
                @if (Session::has('success_message'))
                    <div class="alert alert-success">{{Session::get('success_message')}}</div>
                @endif
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
                        <!--<select class="form-select">-->
                        <!--    <option>Select</option>-->
                        <!--</select>-->
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table" width="100%" id="taskfilterresult">
                        <thead>
                            <tr>
                                <th width="4%"><input type="checkbox"></th>
                                <th width="30%">@if(Session::has('lang') && Session::get('lang')=='bn') নাম @else Name @endif</th>                              
                                <th width="21%">@if(Session::has('lang') && Session::get('lang')=='bn') এডিট/ডিলিট @else Action @endif</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($roles as $role)
                            <tr style="border-bottom:1px solid rgba(0, 0, 0, 0.125)">
                                <td><input type="checkbox" name="id" value="{{$role->id}}"></td>
                                <td>{{$role->name}}</td>
                                <td>
                                    <a href="{{route('admin.deleterole',$role->id)}}" style="float:right;margin-right:5px" class="btn btn-primary">@if(Session::has('lang') && Session::get('lang')=='bn') ডিলিট @else Delete @endif</a>                                   
                                    <a href="{{URL::to('/')}}/role-and-permission/{{encrypt($role->id)}}/edit" style="float:right;margin-right:5px;" class="btn btn-info">@if(Session::has('lang') && Session::get('lang')=='bn') এডিট @else Edit @endif</a>                              
                                    <a href="{{URL::to('/')}}/role-and-permission/{{encrypt($role->id)}}/permission" style="float:right;margin-right:5px;" class="btn btn-secondary">@if(Session::has('lang') && Session::get('lang')=='bn') অনুমতি @else Permission @endif</a>
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
    </script>
@endpush