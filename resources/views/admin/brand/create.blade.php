@extends('admin.layouts.main')
@section('content')
<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
<div class="container-fluid navbars" style="background-image: linear-gradient(53deg, #42424a 0%, #191919 100%);">
    <div class="row">
        <div class="col-md-12">
            <nav style="--bs-breadcrumb-divider: '';" aria-label="breadcrumb">
                <ol class="breadcrumb" style="background-color:transparent;color:#fff">
                    <li class="breadcrumb-item">
                        <a href="{{URL::to('/')}}/products">
                            <img src="{{URL::to('/')}}/img/cubes.png"> <br> Products
                        </a>
                    </li>
                    <li class="breadcrumb-item" aria-current="page">
                        <a href="{{URL::to('/')}}/category">
                            <img src="{{URL::to('/')}}/img/categories1.png" > <br>Categories
                        </a>
                    </li>
                    <li class="breadcrumb-item" aria-current="page">
                        <a href="{{route('admin.subcategory.index')}}">
                            <img src="{{URL::to('/')}}/img/subcategory.png" > <br>Sub Categories
                        </a>
                    </li>
                    <li class="breadcrumb-item" aria-current="page">
                        <a href="{{URL::to('/')}}/attribute">
                            <img src="{{URL::to('/')}}/img/sort-descending.png" ><br>Attributes
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <a href="{{URL::to('/')}}/brand">
                            <img src="{{URL::to('/')}}/img/brand.png" > <br>Brands
                        </a>
                    </li>
                    
                    <li class="breadcrumb-item" aria-current="page">
                        <a href="{{URL::to('/')}}/supplier">
                            <img src="{{URL::to('/')}}/img/ribbon.png" > <br>Suppliers
                        </a>
                    </li>
                    <!--<li class="breadcrumb-item" aria-current="page">-->
                    <!--    <a href="#">-->
                    <!--        <img src="{{URL::to('/')}}/img/collection.png" > <br>Collections-->
                    <!--    </a>-->
                    <!--</li>-->
                    <!--<li class="breadcrumb-item" aria-current="page">-->
                    <!--    <a href="#">-->
                    <!--        <img src="{{URL::to('/')}}/img/browser-tab.png" > <br>Global Tabs-->
                    <!--    </a>-->
                    <!--</li>-->
                </ol>
            </nav>
        </div>
    </div>
</div>
<div class="container-fluid mt-4" id="toplist">
    <div class="row">
        <div class="col-md-6">
            <h4>Add Category</h4>
        </div>
        <div class="col-md-6">
            <ul>
                <li class="active"><a href="{{URL::to('/')}}/category">Back to List</a></li>
                <li><a href="">Import</a></li>
                <li><a href="">Export</a></li>
            </ul>
        </div>
    </div>
    <div class="row mt-5 productlist">
        <div class="col-12">
        <div class="card">
            <div class="card-header">
            </div>
            <div class="card-body">
            <form action="{{URL::to('category')}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="mb-3 row">
                    <label for="staticEmail" class="col-md-2 col-form-label">Name</label>
                    <div class="col-md-4">
                    <input type="text" class="form-control" id="staticEmail" name="name" placeholder="Category Name">
                        @error('name')
                            <p class="text-danger">{{$message}}</p>
                        @enderror
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="inputPassword" class="col-sm-2 col-form-label">Parent</label>
                    <div class="col-sm-4">
                    <select class="form-select" name="parent">
                        <option value="0">Select as Parent</option>
                        <?php
                        $categories=DB::table('categories')->where('parent',0)->get();
                        ?>
                        @foreach($categories as $cats)
                        <option value="{{$cats->id}}">{{$cats->name}}</option>
                        <?php
                        $subcats=DB::table('categories')->where('parent',$cats->id)->get();
                        ?>
                        @if(isset($subcats))
                        @foreach($subcats as $subcat)
                        <option value="{{$subcat->id}}">--{{$subcat->name}}</option>
                        @endforeach
                        @endif
                        @endforeach
                    </select>
                    @error('parent')
                            <p class="text-danger" role="alert">{{$message}}</p>
                        @enderror
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="icon" class="col-md-2 col-form-label">Icon</label>
                    <div class="col-md-4">
                    <input type="file" class="form-control" id="icon" name="icon">
                    @error('icon')
                            <p class="text-danger" role="alert">{{$message}}</p>
                        @enderror
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="banner" class="col-md-2 col-form-label">Banner</label>
                    <div class="col-md-4">
                    <input type="file" class="form-control" id="banner" name="banner">
                    @error('banner')
                            <p class="text-danger" role="alert">{{$message}}</p>
                        @enderror
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="staticEmail" class="col-md-2 col-form-label">Status</label>
                    <div class="col-md-4">
                    <div class="form-check form-switch is-filled" style="text-align:center;">
                        <input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked" name="status" style="margin:0 auto;" checked="">
                        <label class="form-check-label" for="flexSwitchCheckChecked"></label>
                    </div>
                    @error('status')
                            <p class="text-danger" role="alert">{{$message}}</p>
                        @enderror
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="position" class="col-md-2 col-form-label">Position</label>
                    <div class="col-md-4">
                    <input type="number" class="form-control" id="position" name="position" placeholder="0">
                    @error('position')
                            <p class="text-danger" role="alert">{{$message}}</p>
                        @enderror
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="position" class="col-md-2 col-form-label"></label>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-info">Submit</button>
                    </div>
                </div>
                </form>
            </div>
        </div>

        </div>
    </div>
</div>
</main>
@endsection