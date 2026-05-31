@extends('admin.layouts.main')


@section('content')
    <main class="main-content position-relative  h-100 border-radius-lg">

        {{-- Page top bar menu --}}
        @include('admin.courier.layouts.top_bar')

        <div class="container-fluid mt-4" id="toplist">

            {{-- Header section --}}
            <div class="row">
                <div class="col-md-6">
                    <h4>Steadfast Courier Setup</h4>
                </div>
            </div>

            {{-- card section --}}
            <div class="row mt-1">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-4 mx-auto">
                                    <form action="{{route('courier.courierStore', ["name" => "steadfast"])}}"
                                          method="post">
                                        @csrf
                                        <div>
                                            <input type="hidden" name="name" value="steadfast">
                                            {{--                                            <div class="form-group">--}}
                                            {{--                                                <label for="">Username</label>--}}
                                            {{--                                                <input type="text" name="username"--}}
                                            {{--                                                       class="form-control"--}}
                                            {{--                                                       value="{{ $courier->username ?? "" }}">--}}
                                            {{--                                                @error('username')--}}
                                            {{--                                                <p class="text-danger" role="alert">{{ $message }}</p>--}}
                                            {{--                                                @enderror--}}
                                            {{--                                            </div>--}}
                                            {{--                                            <div class="form-group">--}}
                                            {{--                                                <label for="">Password</label>--}}
                                            {{--                                                <input type="text" name="password"--}}
                                            {{--                                                       class="form-control"--}}
                                            {{--                                                       value="{{ $courier->password ?? "" }}">--}}
                                            {{--                                                @error('password')--}}
                                            {{--                                                <p class="text-danger" role="alert">{{ $message }}</p>--}}
                                            {{--                                                @enderror--}}
                                            {{--                                            </div>--}}
                                            <div class="form-group">
                                                <label for="">API Key</label>
                                                <input type="text" name="api_key"
                                                       class="form-control"
                                                       value="{{ $courier->api_key ?? "" }}">
                                                @error('api_key')
                                                <p class="text-danger" role="alert">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div class="form-group mt-3">
                                                <label for="">Secret Key</label>
                                                <input type="text" name="api_secret"
                                                       class="form-control"
                                                       value="{{ $courier->api_secret ?? "" }}">
                                                @error('api_secret')
                                                <p class="text-danger" role="alert">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div
                                                class="form-group form-check form-switch d-flex align-items-center mt-3"
                                                style="padding-left: 0;margin-top: 10px;">
                                                <label for="statusCheckbox">Status</label>
                                                <input class="form-check-input"
                                                       type="checkbox"
                                                       id="statusCheckbox"
                                                       name="status"
                                                       style="margin-left: 20px; margin-top: -10px;"
                                                       @if(isset($courier->status) && $courier->status == 1) checked @endif
                                                >
                                            </div>
                                            <div class="form-group my-4">
                                                <button type="submit" class="btn btn-primary">Save
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </main>
@endsection
