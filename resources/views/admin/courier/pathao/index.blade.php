@extends('admin.layouts.main')


@section('content')
    <main class="main-content position-relative  h-100 border-radius-lg">

        {{-- Page top bar menu --}}
        @include('admin.courier.layouts.top_bar')

        <div class="container-fluid mt-4" id="toplist">

            {{-- Header section --}}
            <div class="row">
                <div class="col-md-6">
                    <h4>Pathao Courier Setup</h4>
                </div>
            </div>

            {{-- card section --}}
            <div class="row mt-1">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-4 mx-auto">
                                    <form action="{{route('courier.courierStore', ["name" => "pathao"])}}"
                                          method="post">
                                        @csrf
                                        <div>
                                            <input type="hidden" name="name" value="pathao">

                                            <div class="form-group">
                                                <label for="">Store ID</label>
                                                <input type="text" name="courier_store_id"
                                                       class="form-control"
                                                       value="{{ $courier->courier_store_id ?? "" }}">
                                                @error('courier_store_id')
                                                <p class="text-danger" role="alert">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div class="form-group mt-3">
                                                <label for="">Username</label>
                                                <input type="text" name="username"
                                                       class="form-control"
                                                       value="{{ $courier->username ?? "" }}">
                                                @error('username')
                                                <p class="text-danger" role="alert">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div class="form-group mt-3">
                                                <label for="">Password</label>
                                                <input type="text" name="password"
                                                       class="form-control"
                                                       value="{{ $courier->password ?? "" }}">
                                                @error('password')
                                                <p class="text-danger" role="alert">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div class="form-group mt-3">
                                                <label for="">Client ID</label>
                                                <input type="text" name="api_key"
                                                       class="form-control"
                                                       value="{{ $courier->api_key ?? "" }}">
                                                @error('api_key')
                                                <p class="text-danger" role="alert">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div class="form-group mt-3">
                                                <label for="">Client Secret</label>
                                                <input type="text" name="api_secret"
                                                       class="form-control"
                                                       value="{{ $courier->api_secret ?? "" }}">
                                                @error('api_secret')
                                                <p class="text-danger" role="alert">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div class="form-group mt-3">
                                                @php
                                                    $store_url = env("APP_URL") ?? "https://admin.ebitans.com";
                                                    if (!preg_match("~^(?:f|ht)tps?://~i", $store_url)) {
                                                        $store_url = "https://" . $store_url;
                                                    }

                                                    $secretKey = env('PATHAO_WEBHOOK_SECRET') ?? "";
                                                    $dataToSign = env('PATHAO_WEBHOOK_SECRET_KEY') ?? "";
                                                    $generateSignature = hash_hmac('sha256', $dataToSign, $secretKey);
                                                @endphp

                                                @if(isset($store_url))
                                                    <small>Your webhook URL is: <span
                                                            class="text-info">{{ $store_url."/webhook/pathao" }}</span>
                                                    </small>
                                                    <br/>
                                                @endif
                                                @if(isset($generateSignature))
                                                    <small>Your webhook Secret is: <span
                                                            class="text-info">{{ $generateSignature }}</span>
                                                    </small>
                                                @endif
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
