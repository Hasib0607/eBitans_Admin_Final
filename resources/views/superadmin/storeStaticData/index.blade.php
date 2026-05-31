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
                    <h4>Store Static Data</h4>
                </div>

                <form action="{{ route('super_admin.settings.save.store.static.data') }}" method="POST"
                      enctype="multipart/form-data">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-body" style="border:none">
                            <div class="form-group">
                                <div class="mb-4">
                                    <textarea name="data" id="data" cols="30" rows="10">{{ $content ?? "" }}</textarea>
                                    @error('data')
                                    <p class="text-danger" role="alert">{{ $message }}</p>
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
