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
    </style>
    <main class="main-content position-relative h-100 border-radius-lg">
        <div class="container-fluid navbars"
             style="background-image: linear-gradient(53deg, #42424a 0%, #191919 100%);">
            <div class="row new">
                <div class="col-md-12">
                    <nav style="--bs-breadcrumb-divider: '';" aria-label="breadcrumb">
                        <ol class="breadcrumb" style="background-color:transparent;color:#fff">
                            <li class="breadcrumb-item active">
                                <a href="{{route('admin.staff')}}">
                                    <img src="{{URL::to('/')}}/img/icons/employee.png"> <br> <span
                                        class="nav-link-text ms-1">@if(Session::has('lang') && Session::get('lang')=='bn')
                                            স্টাফ
                                        @else
                                            Employee
                                        @endif</span>
                                </a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{route('admin.role.permission')}}">
                                    <img src="{{URL::to('/')}}/img/icons/permissions.png"> <br> <span
                                        class="nav-link-text ms-1">@if(Session::has('lang') && Session::get('lang')=='bn')
                                            ভূমিকা এবং অনুমতি
                                        @else
                                            Role & Permission
                                        @endif</span>
                                </a>
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <section class="container content-main">
            <div class="row">
                <form action="{{route('admin.updatestaff',$singleData->id)}}" method="post"
                      enctype="multipart/form-data">
                    <input type="hidden" name="index" value="1" id="index">
                    @csrf
                    <div class="row">
                        <div class="col-lg-9 mt-4 mb-4">
                            <div class="content-header row">
                                <div class="col-md-6">
                                    <h2 class="content-title">@if(Session::has('lang') && Session::get('lang')=='bn')
                                            এডিট স্টাফ
                                        @else
                                            Edit Staff
                                        @endif </h2>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-8">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h4>@if(Session::has('lang') && Session::get('lang')=='bn')
                                            মৌলিক
                                        @else
                                            Basic
                                        @endif</h4>
                                </div>
                                <div class="card-body">

                                    <div class="row mb-4">
                                        <label for="product_name"
                                               class="form-label">@if(Session::has('lang') && Session::get('lang')=='bn')
                                                নাম
                                            @else
                                                Name
                                            @endif <span class="req">*</span></label>
                                        <div class="col-md-8">
                                            <input type="text" placeholder="Type here" class="form-control" id="name"
                                                   name="name" value="{{$staff->name ?? ""}}">
                                            @error('name')
                                            <p class="text-danger" role="alert">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <label for="product_name"
                                               class="form-label">@if(Session::has('lang') && Session::get('lang')=='bn')
                                                ব্যবহারকারীর নাম
                                            @else
                                                Username
                                            @endif <span class="req">*</span></label>
                                        <div class="col-md-8">
                                            <input type="text" placeholder="Type here" class="form-control"
                                                   id="username" name="username" value="{{$staff->username ?? ""}}">
                                            @error('username')
                                            <p class="text-danger" role="alert">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <label for="product_name"
                                               class="form-label">@if(Session::has('lang') && Session::get('lang')=='bn')
                                                ইমেইল
                                            @else
                                                Email
                                            @endif </label>
                                        <div class="col-md-8">
                                            <input type="email" placeholder="Type here" class="form-control" id="email"
                                                   name="email" value="{{$staff->email ?? ""}}">
                                            @error('email')
                                            <p class="text-danger" role="alert">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <label for="product_name"
                                               class="form-label">@if(Session::has('lang') && Session::get('lang')=='bn')
                                                ফোন
                                            @else
                                                Phone
                                            @endif </label>
                                        <div class="col-md-8">
                                            <input type="number" placeholder="Type here" class="form-control" id="phone"
                                                   name="phone" value="{{$staff->phone ?? ""}}">
                                            @error('phone')
                                            <p class="text-danger" role="alert">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <label for="product_name"
                                               class="form-label">@if(Session::has('lang') && Session::get('lang')=='bn')
                                                পাসওয়ার্ড
                                            @else
                                                Password
                                            @endif  </label>
                                        <div class="col-md-8">
                                            <input type="password" placeholder="Type here" class="form-control"
                                                   id="password" name="password">
                                            @error('password')
                                            <p class="text-danger" role="alert">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <label for="product_name"
                                               class="form-label">@if(Session::has('lang') && Session::get('lang')=='bn')
                                                পাস্
                                            @else
                                                Pos
                                            @endif </label>
                                        <div class="col-md-8">
                                            <ul style="list-style:none;">
                                                    <?php
                                                    if (Auth::user()->type == 'admin' || Auth::user()->type == 'dropshipper') {
                                                        $customer = DB::table('customers')->where('uid', Auth::user()->id)->first();
                                                        $store_id = $customer->active_store;
                                                    } elseif (Auth::user()->type == 'staff') {
                                                        $staff = DB::table('staff')->where('uid', Auth::user()->id)->first();
                                                        $store_id = $staff->store_id;
                                                    }
                                                    $brnc = DB::table('branches')->where('store_id', $store_id)->get();
                                                    ?>
                                                @if(isset($brnc) && count($brnc)>0)
                                                    @foreach($brnc as $br)
                                                            <?php
                                                            $stfs = DB::table('staff')->where('id', $staff->id)->whereRaw('FIND_IN_SET("' . $br->id . '", pos)')->first();

                                                            ?>
                                                        <li>
                                                            <input type="checkbox" name="pos[]"
                                                                   @if(isset($stfs)) checked @endif value="{{$br->id}}">
                                                            <label for="">Pos ({{$br->name}})</label>
                                                        </li>

                                                    @endforeach
                                                @endif
                                            </ul>
                                            @error('password')
                                            <p class="text-danger" role="alert">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <label for="product_name"
                                               class="form-label">@if(Session::has('lang') && Session::get('lang')=='bn')
                                                ভূমিকা আইডি
                                            @else
                                                Role Id
                                            @endif</label>
                                        <div class="col-md-8">
                                            <select class="form-control" name="roleid" id="roleid">
                                                    <?php
                                                    $role = DB::table('roles')->where('store_id', $store_id)->get();
                                                    ?>
                                                @if(isset($role) && count($role)>0)
                                                    @foreach($role as $rl)
                                                        <option value="{{$rl->id}}"
                                                                @if(isset($singleData->role_id) && $singleData->role_id==$rl->id) selected @endif>{{$rl->name}}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            @error('roleid')
                                            <p class="text-danger" role="alert">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <button type="submit"
                                            class="btn btn-info mt-4 ml-3">@if(Session::has('lang') && Session::get('lang')=='bn')
                                            আপডেট
                                        @else
                                            Update
                                        @endif </button>

                                </div>
                            </div> <!-- card end// -->

                        </div>
                    </div>

                </form>
            </div>
        </section>
        </div>
    </main>
@endsection

@push('scripts')

    <script>

        jQuery('select[name="category"]').on('change', function () {
            debugger;
            var val = $(this).val();
            console.log(val);
            $('#subcategory').empty();
            var catid = $('select[name="category"]').val();
            $.get('/getsubcat', {catid: catid}, function (data) {
                console.log(data);
                for (var i = 0; i < data.length; i++) {
                    $('#subcategory').append(
                        '<option value="">select</option><option value="' + data[i].id + '">' + data[i].name + '</option>'
                    );
                }
            });
        });
    </script>
@endpush
