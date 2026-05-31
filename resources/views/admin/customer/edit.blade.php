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
                                <a href="{{route('admin.customer')}}">
                                    <img src="{{URL::to('/')}}/img/icons/rating.png"> <br> <span
                                        class="nav-link-text ms-1">@if(Session::has('lang') && Session::get('lang')=='bn')
                                            ক্রেতা
                                        @else
                                            Customers
                                        @endif</span>
                                </a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{route('admin.review')}} ">
                                    <img src="{{URL::to('/')}}/img/icons/reviews.png"> <br> <span
                                        class="nav-link-text ms-1">@if(Session::has('lang') && Session::get('lang')=='bn')
                                            রিভিউ
                                        @else
                                            Reviews
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
                <form action="#{{route('admin.updatecustomer',$singleData->id)}}" method="post"
                      enctype="multipart/form-data">
                    <input type="hidden" name="index" value="1" id="index">
                    @csrf
                    <div class="row">
                        <div class="col-lg-9 mt-4 mb-4">
                            <div class="content-header row">
                                <div class="col-md-6">
                                    <h2 class="content-title">@if(Session::has('lang') && Session::get('lang')=='bn')
                                            এডিট ক্রেতা
                                        @else
                                            Edit Customer
                                        @endif</h2>
                                </div>

                                <div class="col-md-6" style="text-align:right">
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
                                                ছবি
                                            @else
                                                Image
                                            @endif</label>
                                        <div class="col-md-8">
                                            <img src="{{URL::to('/')}}/assets/images/img/{{ $singleData->image }}"
                                                 width="120px">
                                            <br>
                                            <br>
                                            {{-- <input type="file" placeholder="Type here" class="form-control" id="image" name="image" disabled> --}}
                                            @error('image')
                                            <p class="text-danger" role="alert">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row mb-4">
                                        <label for="product_name"
                                               class="form-label">@if(Session::has('lang') && Session::get('lang')=='bn')
                                                নাম
                                            @else
                                                Name
                                            @endif <span class="req">*</span></label>
                                        <div class="col-md-8">
                                            <input type="text" placeholder="Type here" class="form-control" id="name"
                                                   name="name" value="{{$singleData->name}}" disabled>
                                            @error('name')
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
                                            @endif</label>
                                        <div class="col-md-8">
                                            <input type="email" placeholder="Type here" class="form-control" id="email"
                                                   name="email" value="{{$singleData->email}}" disabled>
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
                                            @endif</label>
                                        <div class="col-md-8">
                                            <input type="number" placeholder="Type here" class="form-control" id="phone"
                                                   name="phone" value="{{$singleData->phone}}" disabled>
                                            @error('phone')
                                            <p class="text-danger" role="alert">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    {{-- <div class="row mb-4">
                                        <label for="product_name" class="form-label">@if(Session::has('lang') && Session::get('lang')=='bn') পাসওয়ার্ড @else Password @endif </label>
                                        <div class="col-md-8">
                                        <input type="password" placeholder="Type here" class="form-control" id="password" name="password" disabled>
                                        @error('password')
                                                <p class="text-danger" role="alert">{{$message}}</p>
                                        @enderror
                                        </div>
                                    </div> --}}

                                    <div class="row mb-4">
                                        <label for="product_name"
                                               class="form-label">@if(Session::has('lang') && Session::get('lang')=='bn')
                                                ঠিকানা
                                            @else
                                                Address
                                            @endif</label>
                                        <div class="col-md-8">
                                            <textarea type="text" placeholder="Type here" class="form-control"
                                                      id="country" name="address"
                                                      disabled>{{$singleData->address ?? ""}}</textarea>
                                            @error('address')
                                            <p class="text-danger" role="alert">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    {{-- <button type="submit" class="btn btn-info mt-4 ml-3">@if(Session::has('lang') && Session::get('lang')=='bn') আপডেট @else Update @endif </button> --}}

                                    @php
                                        $userData = getUserData();
                                        $store_id = $userData['store_id'];
                                        $user_id = $singleData->id;
                                            $blockStatus = \App\Models\BlockUser::where('store_id', $store_id)->where('user_id', $singleData->id)->first();
                                    @endphp
                                    <div class="col-lg-8">
                                        <div class="row">
                                            <label for="staticEmail" class="col-md-3 col-form-label">
                                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                    অর্ডার ব্লক
                                                @else
                                                    Order Block
                                                @endif
                                            </label>
                                            <div class="col-md-2">
                                                <div class="form-check form-switch is-filled"
                                                     style="text-align:center;padding-top:14px;">
                                                    <input class="form-check-input" type="checkbox"
                                                           id="flexSwitchCheckChecked" name="status"
                                                           data-id="{{ $singleData->id }}"
                                                           style="margin: 0 0 0 -50px;"
                                                           @if(isset($blockStatus) && $blockStatus->status == 1) checked @endif>
                                                    <label class="form-check-label"
                                                           for="flexSwitchCheckChecked"></label>
                                                </div>
                                            </div>
                                            @error('status')
                                            <p class="text-danger" role="alert">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                </div>
                            </div> <!-- card end// -->

                        </div>
                    </div>

                </form>
            </div>

            <div class="row">
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header pb-0">
                                <div class="row">
                                    <div class="col-lg-12 col-12">
                                        <h6
                                            style="padding-top: 10px;padding-bottom: 10px;padding-left:10px;background-image: linear-gradient(195deg, #b5b5b5 0%, #485059 100%);color: #fff;border-radius:0.75rem">
                                            <span class="nav-link-text ms-1">
                                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                    অর্ডার পরিসংখ্যান
                                                @else
                                                    Customer Order Statistic
                                                @endif
                                            </span>
                                        </h6>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body px-4 pb-2">
                                <div class="table-responsive" style="padding-bottom: 20px;">
                                    <table class="table table-striped" style="margin-bottom:30px;" width="100%">
                                        <tbody>
                                        <tr>
                                            <th width="25%">Pending</th>
                                            <td width="25%">
                                                {{ $orderHistory->pending ?? 0 }}</td>
                                            <th width="25%">On Hold</th>
                                            <td width="25%">
                                                {{ $orderHistory->on_hold ?? 0 }}</td>
                                        </tr>
                                        <tr>
                                            <th>Payment Failed</th>
                                            <td>{{ $orderHistory->payment_failed ?? 0 }}
                                            </td>
                                            <th>Processing</th>
                                            <td>{{ $orderHistory->processing ?? 0 }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Shipping</th>
                                            <td>{{ $orderHistory->shipping ?? 0 }}
                                            </td>
                                            <th>Delivered</th>
                                            <td>{{ $orderHistory->delivered ?? 0 }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Returned</th>
                                            <td>{{ $orderHistory->returned ?? 0 }}
                                            </td>
                                            <th>Cancel</th>
                                            <td>{{ $orderHistory->cancelled ?? 0 }}
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
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

        $(document).ready(function () {
            $("#flexSwitchCheckChecked").on("change", function () {
                var route = "{{ route('admin.customerBlockStatusChange', ['id' => '__id__']) }}";
                var value = $(this).val();
                var id = $(this).data('id');

                let url = route.replace('__id__', id);

                $.get(url, {
                    value: value,
                    id: id
                }, function (data) {
                    if (data.status) {
                        swal.fire(
                            'Success',
                            data.message,
                            'success'
                        )
                    } else {
                        swal.fire(
                            'Error',
                            data.message,
                            'error'
                        )
                    }
                });
            });
        });
    </script>
@endpush
