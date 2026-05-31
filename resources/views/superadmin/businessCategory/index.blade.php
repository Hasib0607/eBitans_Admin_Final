@extends('admin.layouts.main')
@section('content')
    <main class="main-content position-relative h-100 border-radius-lg">

        @include('superadmin.share.design-top-nav')


        <div class="modal fade" id="categoryModal" tabindex="-1" aria-labelledby="categoryLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form action="{{ route('super_admin.business_category.store') }}" method="POST"
                      enctype="multipart/form-data">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h6>Category Create</h6>
                        </div>
                        <div class="modal-body" style="border:none">
                            <div class="form-group">
                                <div class="mb-4">
                                    <label for="category_name" class="form-label">Category Name</label>
                                    <input type="text" placeholder="Category Name" class="form-control"
                                           id="category_name" name="name">
                                    @error('name')
                                    <p class="text-danger" role="alert">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="mb-4">
                                    <label for="category_slug" class="form-label">Category Slug</label>
                                    <input type="text" placeholder="Category Slug" class="form-control"
                                           id="category_slug" name="slug">
                                    @error('slug')
                                    <p class="text-danger" role="alert">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="parent_id"
                                       class="form-label">Parent Category</label>
                                <select name="parent_id" class="form-control">
                                    @if(isset($parentCategories) && count($parentCategories))
                                        <option value="">Select Parent</option>
                                        @foreach($parentCategories as $item)
                                            <option
                                                value="{{ $item->id ?? "" }}">{{ $item->name ?? "" }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                @error('parent_id')
                                <p class="text-danger"
                                   role="alert">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-primary" type="submit">Save</button>
                            <button class="btn btn-danger" type="button" data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>


        <div class="container-fluid mt-4" id="toplist">
            <div class="row">
                <div class="col-md-6">
                    <h4>All Category</h4>
                </div>
                <div class="col-md-6">
                    <ul>
                        <li style="padding:0px;border:0px;">
                            <span
                                data-bs-toggle="modal" data-bs-target="#categoryModal"
                                class="btn btn-primary"
                                style="display:block; border-radius:0px !important">
                                Create New
                            </span>
                        </li>
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
                                        <th width="4%"><input type="checkbox" name="ids" id="checkedAll"></th>
                                        <th width="20%">Image</th>
                                        <th width="10%">Name</th>
                                        <th width="55%">Slug</th>
                                        <th width="11%">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($categories as $key=>$category)
                                        <div class="modal fade" id="category{{ $key }}" tabindex="-1"
                                             aria-labelledby="categoryLabel" aria-hidden="true">
                                            <div class="modal-dialog ">
                                                <form
                                                    action="{{ route('super_admin.business_category.update', ['id'=>$category->id]) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('PUT')

                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h6>Category Edit</h6>
                                                        </div>
                                                        <div class="modal-body" style="border:none">
                                                            <div class="form-group">
                                                                <div class="mb-4">
                                                                    <label for="category{{ $key }}"
                                                                           class="form-label">Category Name</label>
                                                                    <input type="text" placeholder="Category Name"
                                                                           class="form-control"
                                                                           id="name"
                                                                           value="{{ $category->name ?? '' }}"
                                                                           name="name">
                                                                    @error('name')
                                                                    <p class="text-danger"
                                                                       role="alert">{{ $message }}</p>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <div class="mb-4">
                                                                    <label for="category{{ $key }}"
                                                                           class="form-label">Category Slug</label>
                                                                    <input type="text" placeholder="Category Slug"
                                                                           class="form-control"
                                                                           id="slug"
                                                                           value="{{ $category->slug ?? '' }}"
                                                                           name="slug">
                                                                    @error('slug')
                                                                    <p class="text-danger"
                                                                       role="alert">{{ $message }}</p>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="parent_id{{ $key }}"
                                                                       class="form-label">Parent Category</label>
                                                                <select name="parent_id" class="form-control">
                                                                    @if(isset($parentCategories) && count($parentCategories))
                                                                        <option value="">Select Parent</option>
                                                                        @foreach($parentCategories as $item)
                                                                            <option
                                                                                value="{{ $item->id ?? "" }}" {{ (isset($category->parent_id) && (int)$item->id === (int)$category->parent_id) ? 'selected' : '' }}>{{ $item->name ?? "" }}</option>
                                                                        @endforeach
                                                                    @endif
                                                                </select>
                                                                @error('parent_id')
                                                                <p class="text-danger"
                                                                   role="alert">{{ $message }}</p>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button class="btn btn-primary" type="submit"> Update
                                                            </button>
                                                            <span class="btn btn-danger"
                                                                  data-bs-dismiss="modal">Cancel</span>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                        <tr style="border-bottom:1px solid rgba(0, 0, 0, 0.125)">
                                            <td><input type="checkbox" name="selectedid" value="{{$category->id}}"
                                                       id="id" class="checkSingle"></td>
                                            <td>
                                                <img src="{{URL::to('/')}}/assets/images/design/{{$category->image}}"
                                                     alt="{{$category->name}}" width="100px">
                                            </td>
                                            <td>{{$category->name}}</td>
                                            <td>{{$category->slug}}</td>
                                            <td>
                                                <span class="cursor-pointer" data-bs-toggle="modal"
                                                      data-bs-target="#category{{ $key }}">
                                                    <img src="{{asset('img/edit.png')}}" width="20px" height="20px">
                                                </span>
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
@push('script')
    <script>
        var myModal = document.getElementById('myModal')
        var myInput = document.getElementById('myInput')

        myModal.addEventListener('shown.bs.modal', function () {
            myInput.focus()
        })

        function modalRE(val) {
            $('.show').removeClass("modal-backdrop");
            $('#category' + val).toggle();
        }
    </script>
@endpush
