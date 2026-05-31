@extends('admin.layouts.main')
@section('content')
    <!-- Modal -->
    <style>
        .fade:not(.show) {
            opacity: 0 !important;
        }
    </style>


    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <div class="container-fluid navbars"
             style="background-image: linear-gradient(53deg, #42424a 0%, #191919 100%);">
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
                            <li class="breadcrumb-item active">
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
                                        <th>Store Name</th>
                                        <th>Package</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if(isset($data) && count($data)>0)
                                        @foreach($data as $key=>$dm)
                                            <tr style="border-bottom:1px solid rgba(0, 0, 0, 0.125)">
                                                {{-- <td><input type="checkbox" name="id" value="{{$dm->id}}"></td> --}}
                                                <td>
                                                    {{$dm->getStore->name ?? ""}} <br>
                                                    {{$dm->getStore->getUser->phone ?? ""}}
                                                </td>
                                                <td>{{ $dm->getStore->getPlan->name??'' }}</td>
                                                <td>{{$dm->status}}</td>
                                                <td>{{ date('j M, Y', strtotime($dm->created_at ?? '1971-08-15')) }}</td>
                                                <td>
                                                    <a class="btn btn-secondary"
                                                       href="{{ route('staff.view.setup.data', ['id' => $dm->store_id]) }}">
                                                        View
                                                    </a>

                                                    <a class="btn btn-primary"
                                                       @if($dm->status=='Pending')
                                                           href="{{URL::to('/')}}/websitesetup/{{$dm->id}}/Processing"
                                                       @elseif($dm->status=='Processing')
                                                           href="{{URL::to('/')}}/websitesetup/{{$dm->id}}/Working"
                                                       @elseif($dm->status=='Working')
                                                           href="{{URL::to('/')}}/websitesetup/{{$dm->id}}/Complete"
                                                       @else
                                                           href="#"
                                                        @endif
                                                    >
                                                        @if($dm->status=='Pending')
                                                            Processing
                                                        @elseif($dm->status=='Processing')
                                                            Working
                                                        @elseif($dm->status=='Working')
                                                            Complete
                                                        @else
                                                            Complete
                                                        @endif
                                                    </a>

                                                    <button onclick="LoginModal({{$dm->getStore->id??0}})"
                                                            class="btn btn-info">Login
                                                    </button>
                                            </tr>
                                        @endforeach
                                    @endif
                                    </tbody>
                                </table>
                                <div style="text-align: center;">
                                    {!! $data->links() !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        {{-- modal   --}}
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form method="post" action="{{route('staff.webSetUpLogin')}}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel"> Store Login </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">

                            <input type="hidden" id="store_id" name="store_id" value="">
                            <div class="form-group py-3">
                                <label for="access_key">Store Access Key</label>
                                <input type="text" class="form-control" id="access_key" name="access_key" value=""
                                       placeholder="Enter Client Access Key">
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
            $('#store_id').val(id);
            $('#exampleModal').modal('toggle');
        }
    </script>

    <script>
        $(document).ready(function () {
            $("#taskfilter").on("keyup", function () {
                var value = $(this).val().toLowerCase();
                $("#taskfilterresult tbody tr").filter(function () {
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
