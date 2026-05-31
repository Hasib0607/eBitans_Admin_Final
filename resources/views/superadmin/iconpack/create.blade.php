@extends('admin.layouts.main')
@section('content')
<style>
    .card{
        border:1px solid rgba(222, 226, 230, 0.7);
    }
    .card .card-body {
    font-family: "Roboto", Helvetica, Arial, sans-serif;
    padding: .5rem 1.5rem 1.5rem 1.5rem;
}
.card .card-header{
    padding: .5rem 1.5rem .5rem 1.5rem;
    border-bottom:1px solid rgba(222, 226, 230, 0.7);
}
.size{
    list-style-type:none;

}
.size li{
    float:left;
}
</style>

<main class="main-content position-relative h-100 border-radius-lg">
    @include('superadmin.share.design-top-nav')
    <section class="container content-main">
            <div class="row">
            <form action="{{route('superadmin.iconpack.save')}}" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="index" value="1" id="index">
                            @csrf
                <div class="row">
                <div class="col-lg-9 mt-4 mb-4">
                    <div class="content-header row">
                        <div class="col-md-6">
                            <h2 class="content-title"></h2>
                        </div>

                        <div class="col-md-6" style="text-align:right">
                        </div>
                    </div>
                </div>

                <div class="col-lg-6" style="margin:0 auto;">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h4>Add New Icon</h4>
                        </div>
                        <div class="card-body">

                                <div class="mb-4">
                                    <label for="image" class="form-label">Image</label>
                                    <input type="file" placeholder="Type here" class="form-control" id="image" name="image[]" multiple>
                                    @error('image')
                                            <p class="text-danger" role="alert">{{$message}}</p>
                                    @enderror
                                </div>
                                {{-- <div class="mb-4">
                                    <label for="product_name" class="form-label">Name</label>
                                    <input type="text" placeholder="Type here" class="form-control" id="name" name="name">
                                    @error('name')
                                            <p class="text-danger" role="alert">{{$message}}</p>
                                    @enderror
                                </div>
                                <div class="mb-4">
                                    <label for="product_name" class="form-label">Value</label>
                                    <input type="text" placeholder="Type here" class="form-control" id="value" name="value">
                                    @error('value')
                                            <p class="text-danger" role="alert">{{$message}}</p>
                                    @enderror
                                </div> --}}
                                <div class="mb-4">
                                    <label class="form-label"></label>
                                    <button class="btn btn-info rounded font-sm hover-up" type="submit">Publish</button>
                                </div>

                        </div>
                    </div> <!-- card end// -->

                </div>

                </div>
                </div>

</form>
            </div>
        </section>
    </div>
</main>
@endsection

@push('scripts')
@endpush
