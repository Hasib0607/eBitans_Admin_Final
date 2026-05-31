@extends('admin.layouts.main')
@section('content')
    <style>
        .btn {
            margin-bottom: 0 !important;;
        }

        #data {
            height: 530px;
            width: 100%;
        }
    </style>
    <main class="main-content position-relative h-100 border-radius-lg">
        @include('superadmin.share.settings-top-nav')

        <div class="container-fluid mt-4" id="toplist">
            <div class="row">
                <div class="col-md-6">
                    <h4>Setting</h4>
                </div>

                <form action="{{ route('super_admin.settings.saveSuperAdminSetting') }}" method="POST"
                      enctype="multipart/form-data">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-body" style="border:none">
                            <div class="mb-3 row">
                                <label for="staticEmail" class="col-md-2 col-form-label">
                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                        Domain Connect
                                    @else
                                        Domain Connect
                                    @endif
                                    <span class="req">*</span>
                                </label>
                                <div class="col-md-9">
                                    <div>
                                        <input type="radio" class="cursor-pointer" id="withCpanel"
                                               name="domain_connect_status" value="0"
                                               @if((isset($data['domain_connect_status']) && $data['domain_connect_status'] == "0") || !isset($data['domain_connect_status'])) checked @endif>
                                        <label class="cursor-pointer" for="withCpanel">With Cpanel</label>
                                    </div>
                                    <div>
                                        <input type="radio" class="cursor-pointer" id="withoutCpanel"
                                               name="domain_connect_status" value="1"
                                               @if(isset($data['domain_connect_status']) && $data['domain_connect_status'] == "1") checked @endif>
                                        <label class="cursor-pointer" for="withoutCpanel">Without Cpanel</label>
                                    </div>

                                    @error('name')
                                    <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>


                        </div>
                        <div style="margin: 0 0 20px 30px">
                            <button class="btn btn-primary" type="submit">Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </main>
@endsection
