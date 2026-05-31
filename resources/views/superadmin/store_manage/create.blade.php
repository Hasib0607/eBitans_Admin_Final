@extends('admin.layouts.main')
@push('styles')
<link rel="stylesheet" src="{{asset('admin/src/bootstrap-tagsinput.css')}}" />
@endpush
@section('content')
<style>
.bootstrap-tagsinput {
  width: 100%;
}
.bootstrap-tagsinput {
  background-color: #fff;
  /*border: 1px solid #ccc;*/
  /*box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);*/
  display: inline-block;
  padding: 4px 6px;
  color: #555;
  vertical-align: middle;
  border-radius: 4px;
  max-width: 100%;
  line-height: 22px;
  cursor: text;
}
.bootstrap-tagsinput .tag {
  margin-right: 2px;
  color: white;
}
.label-info {
  background-color: #5bc0de;
}
.label {
  display: inline;
  padding: .2em .6em .3em;
  font-size: 75%;
  font-weight: 700;
  line-height: 1;
  color: #fff;
  text-align: center;
  white-space: nowrap;
  vertical-align: baseline;
  border-radius: .25em;
}
.bootstrap-tagsinput .tag [data-role="remove"] {
  margin-left: 8px;
  cursor: pointer;
}
.bootstrap-tagsinput .tag [data-role="remove"]::after {
  content: "x";
  padding: 0px 2px;
}
.bootstrap-tagsinput .tag [data-role="remove"] {
  cursor: pointer;
}
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
.bootstrap-tagsinput {
	margin: 0;
	width: 100%;
	padding: 0.5rem 0.75rem 0;
	font-size: 1rem;
  line-height: 1.25;
	transition: border-color 0.15s ease-in-out;

	&.has-focus {
    background-color: #fff;
    border-color: #5cb3fd;
	}

	.label-info {
		display: inline-block;
		background-color: #636c72;
		padding: 0 .4em .15em;
		border-radius: .25rem;
		margin-bottom: 0.4em;
	}

	input {
		margin-bottom: 0.5em;
	}
}
.bootstrap-tagsinput .tag [data-role="remove"]:after {
	content: '\00d7';
}
</style>
<main class="main-content position-relative h-100 border-radius-lg">
<div class="container-fluid navbars" style="background-image: linear-gradient(53deg, #42424a 0%, #191919 100%);">
    <div class="row new">
        <div class="col-md-12">
            <nav style="--bs-breadcrumb-divider: '';" aria-label="breadcrumb">
                <ol class="breadcrumb" style="background-color:transparent;color:#fff">
                    <li class="breadcrumb-item active">
                        <a href="{{URL::to('/')}}/products">
                            <img src="{{URL::to('/')}}/img/icons/box.png"> <br> <span class="nav-link-text ms-1">@if(Session::has('lang') && Session::get('lang')=='bn') পণ্য @else Products @endif</span>
                        </a>
                    </li>
                    @if(isset($category) && $category=='1' || Auth::user()->type=='admin')
                    <li class="breadcrumb-item" aria-current="page">
                        <a href="{{URL::to('/')}}/category">
                            <img src="{{URL::to('/')}}/img/icons/categories.png" > <br><span class="nav-link-text ms-1">@if(Session::has('lang') && Session::get('lang')=='bn') ক্যাটাগরি @else Categories @endif</span>
                        </a>
                    </li>
                    @endif
                    @if(isset($subcategory) && $subcategory=='1' || Auth::user()->type=='admin')
                    <li class="breadcrumb-item" aria-current="page">
                        <a href="{{route('admin.subcategory.index')}}">
                            <img src="{{URL::to('/')}}/img/subcategory.png" > <br><span class="nav-link-text ms-1">@if(Session::has('lang') && Session::get('lang')=='bn') সাব ক্যাটাগরি @else Sub Categories @endif</span>
                        </a>
                    </li>
                    @endif
                    @if(isset($attribute) && $attribute=='1' || Auth::user()->type=='admin')
                    <li class="breadcrumb-item" aria-current="page">
                        <a href="{{URL::to('/')}}/attribute">
                            <img src="{{URL::to('/')}}/img/icons/product.png" ><br><span class="nav-link-text ms-1">@if(Session::has('lang') && Session::get('lang')=='bn') পণ্যের ধরণ @else Variants @endif</span>

                        </a>
                    </li>
                    @endif
                    @if(isset($brand) && $brand=='1' || Auth::user()->type=='admin')
                    <li class="breadcrumb-item" aria-current="page">
                        <a href="{{URL::to('/')}}/brand">
                            <img src="{{URL::to('/')}}/img/icons/brand.png" > <br><span class="nav-link-text ms-1">@if(Session::has('lang') && Session::get('lang')=='bn') ব্রান্ড @else Brands @endif</span>
                        </a>
                    </li>
                    @endif

                    @if(isset($supplier) && $supplier=='1' || Auth::user()->type=='admin')
                    <li class="breadcrumb-item" aria-current="page">
                        <a href="{{URL::to('/')}}/supplier">
                            <img src="{{URL::to('/')}}/img/icons/supplier.png" > <br><span class="nav-link-text ms-1">@if(Session::has('lang') && Session::get('lang')=='bn') সরবরাহকারী@else Suppliers @endif</span>
                        </a>
                    </li>
                    @endif
                </ol>
            </nav>
        </div>
    </div>
</div>
    <section class="container content-main">
            <div class="row">
            <form action="{{URL::to('/')}}/product/save" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="index" value="1" id="index">
                            @csrf
                <div class="row">
                <div class="col-lg-9 mt-4 mb-4">
                    <div class="content-header row">
                        <div class="col-md-6">
                            <h2 class="content-title">@if(Session::has('lang') && Session::get('lang')=='bn') নতুন পণ্য যোগ করুন @else Add New Product @endif</h2>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h4>@if(Session::has('lang') && Session::get('lang')=='bn') মৌলিক @else Basic @endif  </h4>
                            <span style="font-size:14px;color:red"> @if(Session::has('lang') && Session::get('lang')=='bn') * চিহ্নিত ক্ষেত্রগুলি বাধ্যতামূলক @else  Fields marked with * are mandatory @endif  </span>
                        </div>
                        <div class="card-body">
                        @if (Session::has('error_message'))
                            <div class="alert alert-danger" style="color:#fff">{{Session::get('error_message')}}</div>
                        @endif
                                <div class="mb-4">
                                    <label for="product_name" class="form-label"> @if(Session::has('lang') && Session::get('lang')=='bn') পণ্য শিরোনাম @else Product title @endif<span class="req">*</span></label>
                                    <input type="text" placeholder="Type here" class="form-control" id="product_name" name="product_name" value="{{old('product_name')}}">
                                    @error('product_name')
                                            <p class="text-danger" role="alert">{{$message}}</p>
                                    @enderror
                                </div>
                                <div class="mb-4">
                                    <label class="form-label">@if(Session::has('lang') && Session::get('lang')=='bn') পূর্ণ বিবরণ @else Full description @endif<span class="req">*</span></label>
                                    <textarea placeholder="Type here" class="form-control" rows="4" name="description">{{old('description')}}</textarea>
                                    @error('description')
                                            <p class="text-danger" role="alert">{{$message}}</p>
                                    @enderror
                                </div>
                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="mb-4">
                                            <label class="form-label">@if(Session::has('lang') && Session::get('lang')=='bn') এস কে ইউ @else SKU @endif<span class="req">*</span></label>
                                            <div class="row gx-2">
                                                <input placeholder="SKU" type="text" class="form-control" name="SKU" value="{{old('SKU')}}">
                                                @error('SKU')
                                                    <p class="text-danger" role="alert">{{$message}}</p>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="mb-4">
                                            <label class="form-label">@if(Session::has('lang') && Session::get('lang')=='bn') নিয়মিত মূল্য @else Regular price @endif<span class="req">*</span></label>
                                            <div class="row gx-2">
                                                <input placeholder="৳" type="number" class="form-control" name="regular_price" value="{{old('regular_price')}}">
                                                @error('regular_price')
                                                    <p class="text-danger" role="alert">{{$message}}</p>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                         <div class="mb-4">
                                                <label class="form-label">@if(Session::has('lang') && Session::get('lang')=='bn') দ্রব্য মূল্য @else Product Cost @endif</label>
                                                <input placeholder="" type="number" class="form-control" name="cost" value="{{old('cost')}}">
                                                @error('cost')
                                                    <p class="text-danger" role="alert">{{$message}}</p>
                                                @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="mb-4">
                                                <label class="form-label">@if(Session::has('lang') && Session::get('lang')=='bn') পরিমাণ @else Quantity @endif<span class="req">*</span></label>
                                                <input placeholder="" type="number" class="form-control" name="quantity" value="{{old('quantity')}}">
                                                @error('quantity')
                                                    <p class="text-danger" role="alert">{{$message}}</p>
                                                @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <label class="form-label">@if(Session::has('lang') && Session::get('lang')=='bn') ডিসকাউন্ট টাইপ @else Discount Type @endif<span class="req">*</span></label>
                                        <select class="form-select" name="discount_type">
                                            <option value="fixed">@if(Session::has('lang') && Session::get('lang')=='bn') ফিক্সড @else  Fixed @endif</option>
                                            <option value="percent">@if(Session::has('lang') && Session::get('lang')=='bn') পার্সেন্ট @else  Percent @endif</option>
                                            <option value="no_discount">@if(Session::has('lang') && Session::get('lang')=='bn') নো ডিসকাউন্ট @else  No Discount @endif</option>
                                        </select>
                                        @error('discount_type')
                                            <p class="text-danger" role="alert">{{$message}}</p>
                                        @enderror
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="mb-4">
                                            <label class="form-label">@if(Session::has('lang') && Session::get('lang')=='bn') ডিসকাউন্ট মূল্য @else Discount price @endif</label>
                                            <input placeholder="৳" type="number" class="form-control" name="promotional_price" value="{{old('promotional_price')}}">
                                            @error('promotional_price')
                                                <p class="text-danger" role="alert">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- <div class="col-lg-4">
                                        <div class="mb-4">
                                            <label class="form-label">Quantity</label>
                                            <input placeholder="" type="number" class="form-control" name="quantity">
                                        </div>
                                    </div> -->
                                </div>
                                <div class="row">

                                    <div class="col-lg-12">
                                        <div class="mb-4">
                                                <label class="form-label">@if(Session::has('lang') && Session::get('lang')=='bn') বার কোড @else  Bar Code @endif</label>
                                                <input placeholder="" type="number" class="form-control" name="barcode" value="{{old('barcode')}}">
                                                @error('barcode')
                                                    <p class="text-danger" role="alert">{{$message}}</p>
                                                @enderror
                                        </div>
                                    </div>
                                </div>
                                <!-- <label class="form-check mb-4">
                                    <input class="form-check-input" type="checkbox" value="">
                                    <span class="form-check-label"> Make a template </span>
                                </label> -->

                        </div>
                    </div> <!-- card end -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-6">
                                    <h4>@if(Session::has('lang') && Session::get('lang')=='bn') পাঠানো @else Shipping @endif</h4>
                                </div>
                                <div class="col-6" style="text-align:right">
                                    <a href="javascript:void(0)" id="shipshow"><i class="fa fa-arrow-down"></i></a>
                                    <a href="javascript:void(0)" id="shiphide"><i class="fa fa-arrow-up"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                                <div class="row" id="shipping-div">
                                    <div class="col-lg-6">
                                        <div class="mb-4">
                                            <label for="product_name" class="form-label">@if(Session::has('lang') && Session::get('lang')=='bn') ওজন  @else Weight @endif</label>
                                            <input type="text" placeholder="kg" class="form-control" id="weight" name="weight" value="{{old('weight')}}">
                                            @error('weight')
                                                <p class="text-danger" role="alert">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-4">
                                            <label for="product_name" class="form-label">@if(Session::has('lang') && Session::get('lang')=='bn') বহন খরচ @else Shipping fees @endif</label>
                                            <input type="number" placeholder="৳" class="form-control" id="shipping_fee" name="shipping_fee" value="{{old('shipping_fee')}}">
                                            @error('shipping_fee')
                                                <p class="text-danger" role="alert">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <label class="form-label">@if(Session::has('lang') && Session::get('lang')=='bn') ট্যাক্সের ধরন @else Tax Type @endif</label>
                                        <select class="form-select" name="tax_type">
                                            <option value="fixed">@if(Session::has('lang') && Session::get('lang')=='bn') ফিক্সড @else  Fixed @endif </option>
                                            <option value="percent">@if(Session::has('lang') && Session::get('lang')=='bn') শতাংশ @else Percent @endif</option>
                                        </select>
                                        @error('tax_type')
                                            <p class="text-danger" role="alert">{{$message}}</p>
                                        @enderror
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-4">
                                            <label class="form-label">@if(Session::has('lang') && Session::get('lang')=='bn') করের হার @else Tax rate @endif</label>
                                            <input placeholder="৳" type="number" class="form-control" name="tax_rate" value="{{old('tax_rate')}}">
                                            @error('tax_rate')
                                                <p class="text-danger" role="alert">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            <!-- </form> -->
                        </div>
                    </div> <!-- card end// -->

                </div>
                <div class="col-lg-3">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h4>@if(Session::has('lang') && Session::get('lang')=='bn') মিডিয়া @else Media @endif<span class="req">*</span></h4>
                        </div>
                        <div class="card-body">
                            <div class="input-upload" style="text-align:center">
                                <label for="image">
                                 <img src="{{URL::to('/')}}/img/upload.svg" alt="" style="max-width: 100px;margin-bottom: 20px;vertical-align: baseline;cursor:pointer">
                                 </label>
                                 <input type="file" class="form-control" id="image" name="image[]" multiple>
                                    @error('image')
                                            <p class="text-danger" role="alert">{{$message}}</p>
                                        @enderror
                                 <br>
                                <!-- <div class="mb-3 row">
                                    <label for="banner" class="col-md-2 col-form-label">Image</label>
                                    <div class="col-md-4">
                                    <input type="file" class="form-control" id="image" name="image">
                                    @error('image')
                                            <p class="text-danger" role="alert">{{$message}}</p>
                                        @enderror
                                    </div>
                                </div> -->
                            </div>
                        </div>
                    </div> <!-- card end// -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h4>@if(Session::has('lang') && Session::get('lang')=='bn') সংগঠন @else Organization @endif</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-12 mb-3">
                                    <label class="form-label">@if(Session::has('lang') && Session::get('lang')=='bn') ক্যাটাগরি @else Category @endif<span class="req">*</span></label>
                                    <?php
                                        $category=DB::table('categories')->where('parent',0)->where('store_id',$store_id)->where('status','active')->get();
                                    ?>
                                    <select class="form-select" name="category" id="category">
                                        <option>@if(Session::has('lang') && Session::get('lang')=='bn') নির্বাচন করুন @else  Select @endif </option>
                                        @foreach($category as $cat)
                                        @isset($cat)
                                        <option value="{{$cat->id}}">{{$cat->name}}</option>
                                        @endif
                                        @endforeach
                                    </select>
                                    @error('category')
                                            <p class="text-danger" role="alert">{{$message}}</p>
                                        @enderror
                                </div>

                                <div class="col-sm-12 mb-3">
                                    <label class="form-label">@if(Session::has('lang') && Session::get('lang')=='bn') সাব ক্যাটাগরি @else Sub-category @endif</label>
                                    <select class="form-select" name="subcategory" id="subcategory">
                                    </select>
                                    @error('subcategory')
                                            <p class="text-danger" role="alert">{{$message}}</p>
                                        @enderror
                                </div>

                                <div class="mb-4">
                                    <label class="form-label">@if(Session::has('lang') && Session::get('lang')=='bn') ব্র্যান্ড @else Brand @endif</label>
                                    <?php
                                        $brands=DB::table('brands')->where('store_id',$store_id)->get();
                                    ?>
                                    <select class="form-select" name="brand" id="brand">
                                        <option value="null">@if(Session::has('lang') && Session::get('lang')=='bn') ব্র্যান্ড নির্বাচন করুন @else Select Brand @endif</option>
                                        @foreach($brands as $brand)
                                        @isset($brand)
                                        <option value="{{$brand->id}}">{{$brand->name}}</option>
                                        @endif
                                        @endforeach
                                    </select>
                                    @error('brand')
                                        <p class="text-danger" role="alert">{{$message}}</p>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label class="form-label">@if(Session::has('lang') && Session::get('lang')=='bn') সরবরাহকারী @else Supplier @endif</label>
                                    <?php
                                        $suppliers=DB::table('suppliers')->where('store_id',$store_id)->get();
                                    ?>
                                    <select class="form-select" name="supplier" id="brand">
                                        <option value="null">@if(Session::has('lang') && Session::get('lang')=='bn') সরবরাহকারী নির্বাচন করুন @else Select Supplier @endif</option>
                                        @foreach($suppliers as $supplier)
                                        @isset($supplier)
                                        <option value="{{$supplier->id}}">{{$supplier->name}}</option>
                                        @endif
                                        @endforeach
                                    </select>
                                    @error('supplier')
                                        <p class="text-danger" role="alert">{{$message}}</p>
                                    @enderror
                                </div>
                                <div class="mb-4">

                                    <!--<div class="bootstrap-tagsinput"> -->
                                    <!--<span class="tag label label-info">Washington<span data-role="remove"></span></span> -->
                                    <!--<span class="tag label label-info">Cairo<span data-role="remove"></span></span> -->
                                    <!--<span class="tag label label-info">tddgfg<span data-role="remove"></span></span>-->
                                    <!--<span class="tag label label-info">drgy<span data-role="remove"></span></span>-->
                                    <!--<input type="text" placeholder="" size="1">-->
                                    <!--</div>-->
                                    <label for="product_name" class="form-label">@if(Session::has('lang') && Session::get('lang')=='bn') ট্যাগ @else Tags @endif</label>
                                    <input type="text" value="{{old('tagss')}}" class="form-control" data-role="tagsinput" name="tagss" style="width:100%;display: block;">

                                    <!--<label for="product_name" class="form-label">Tags</label>-->
                                    <!--<input type="text" class="form-control" name="input">-->
                                    <!--<tags-input ng-model="tags" add-on-paste="true" display-property="text" placeholder="Add a Tag here..." ></tags-input>-->
                                    @error('tags')
                                            <p class="text-danger" role="alert">{{$message}}</p>
                                    @enderror
                                    <!--<div class="table-responsive">-->
                                    <!--    <table id="tag-table" class="table table-hover tag-list">-->
                                    <!--        <tbody>-->
                                    <!--        </tbody>-->
                                    <!--    </table>-->
                                    <!--</div>-->
                                </div>
                                <div class="mb-4">
                                    <label for="product_name" class="form-label">@if(Session::has('lang') && Session::get('lang')=='bn') এসইও কীওয়ার্ড @else SEO Keywords @endif</label>
                                    <input type="text" placeholder="Type here" class="form-control" id="product_name" name="seo" value="{{old('seo')}}">
                                    <!--<input type="text" value="" class="form-control" data-role="tagsinput" name="seo" style="width:100%;display: block;">-->
                                    @error('seo_keywords')
                                            <p class="text-danger" role="alert">{{$message}}</p>
                                        @enderror
                                </div>
                                <div class="mb-2">
                                    <label for="best_sell" class="form-label">
                                        <input type="checkbox" id="best_sell" name="best_sell">&nbsp;&nbsp;Best Sell</label>

                                    <!--<input type="text" value="" class="form-control" data-role="tagsinput" name="seo" style="width:100%;display: block;">-->
                                    @error('best_sell')
                                        <p class="text-danger" role="alert">{{$message}}</p>
                                    @enderror
                                </div>
                                <div class="mb-2">
                                    <label for="feature" class="form-label">
                                        <input type="checkbox" id="feature" name="feature">&nbsp;&nbsp;Feature</label>

                                    <!--<input type="text" value="" class="form-control" data-role="tagsinput" name="seo" style="width:100%;display: block;">-->
                                    @error('feature')
                                        <p class="text-danger" role="alert">{{$message}}</p>
                                    @enderror
                                </div>
                            </div> <!-- row.// -->
                        </div>

                    </div> <!-- card end// -->

                </div>
                <div class="col-lg-9">
                    <div class="card mb-4">
                            <div class="card-header">
                                    <div class="row">
                                <div class="col-6">
                                    <h4>@if(Session::has('lang') && Session::get('lang')=='bn') ভেরিয়েন্ট  @else Attributes @endif</h4>
                                </div>
                                <div class="col-6" style="text-align:right">
                                    <a href="javascript:void(0)" id="attrishow"><i class="fa fa-arrow-down"></i></a>
                                    <a href="javascript:void(0)" id="attrihide"><i class="fa fa-arrow-up"></i></a>
                                </div>
                            </div>
                            </div>
                            <div class="card-body">
                                    <div class="row" id="attri-div">
                                        <div class="col-md-2">
                                           <label for="">@if(Session::has('lang') && Session::get('lang')=='bn') ভেরিয়েন্ট টাইপ @else Variantion Type @endif</label>
                                            <select class="form-control" name="att" id="attributes">
                                                <option value="none">Select</option>
                                                <option value="color">Color & size</option>
                                                <option value="onlycolor">Color</option>
                                                <option value="unit">Unit</option>
                                                <option value="size">Size</option>
                                            </select>
                                        </div>
                                        <div id="colorrss" class="col-lg-12 mt-3">
                                            <div class="table-responsive">
                                            <table class="table table-stripped" id="officers-table">
                                                <tbody >
                                                    <?php $i=0; ?>
                                                <tr  id="new" style="margin-top:5px;">
                                                    <td class="mt-1">
                                                        <label>Color:</label>
                                                        <select name="color[0][]" id="color" class="form-control" step="any">
                                                            <option> Select Color</option>
                                                            <?php
                                                            $colors=DB::table('colors')->where('store_id',$store_id)->get();

                                                            ?>
                                                            @if(isset($colors))
                                                            @foreach($colors as $cl)
                                                                <option value="{{$cl->code}}">{{$cl->name}}</option>
                                                            @endforeach
                                                            @endif
                                                        </select>
                                                    </td>
                                                    <td class="mt-1">

                                                    </td>
                                                    <td class="mt-1">
                                                        <div class="row">
                                                            <div class="col-md-4 mt-1">
                                                                size
                                                            </div>
                                                            <div class="col-md-4 mt-1">
                                                                Quantity
                                                            </div>
                                                            <div class="col-md-4 mt-1">
                                                                Additional Price
                                                            </div>
                                                        </div>
                                                        <?php
                                                        $size=DB::table('sizes')->where('store_id',$store_id)->get();
                                                        ?>
                                                        @if(isset($size))
                                                        @foreach($size as $key=>$sz)
                                                            <div class="row" style="margin-top:5px;">
                                                                <div class="col-md-1 mt-1">
                                                                    <input type="checkbox" name="sid[0][{{$key}}]">
                                                                </div>
                                                                <div class="col-md-3 mt-1">
                                                                    <input type="text" class="form-control" name="size[0][{{$key}}]" value="{{$sz->name}}" readonly>
                                                                </div>
                                                                <div class="col-md-4 mt-1">
                                                                    <input type="number" class="form-control" name="quantitys[0][{{$key}}]" placeholder="Enter Quantity" value="">
                                                                </div>
                                                                <div class="col-md-4 mt-1">
                                                                <input type="number" class="form-control" name="price[0][{{$key}}]" placeholder="Additional Price" value="0">
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                        @endif
                                                    </td>
                                                    <td class="mt-1">
                                                        <a class="remove-officer-button mt-3 " data-bs-toggle="tooltip" data-bs-placement="top" title="Delete"><img src="{{URL::to('/')}}/img/delete.png" alt="" width="30px" style="margin-bottom:5px;"></a>
                                                    <br>
                                                        <a onclick="addRow()" data-bs-toggle="tooltip" data-bs-placement="top" title="Add"><img src="{{URL::to('/')}}/img/add.png" alt="" width="30px"></a>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                            </div>
                                        </div>
                                        <div id="sizess" class="col-lg-12 mt-3">
                                            <div class="table-responsive">
                                            <table class="table table-stripped" id="officers-table2">
                                                <tbody >
                                                    <?php $i=0; ?>
                                                <tr id="new2" style="margin-top:5px;">
                                                    <td class="mt-1">
                                                        <div class="row">
                                                            <div class="col-md-4 mt-1">
                                                                size
                                                            </div>
                                                            <div class="col-md-4 mt-1">
                                                                Quantity
                                                            </div>
                                                            <div class="col-md-4 mt-1">
                                                                Additional Price
                                                            </div>
                                                        </div>
                                                        <?php
                                                        $size=DB::table('sizes')->where('store_id',$store_id)->get();
                                                        ?>
                                                        @if(isset($size))
                                                        @foreach($size as $key=>$sz)
                                                            <div class="row" style="margin-top:5px;">
                                                                <div class="col-md-4 mt-1">
                                                                    <input type="text" class="form-control" name="sizess[]" value="{{$sz->name}}" readonly>
                                                                </div>
                                                                <div class="col-md-4 mt-1">
                                                                    <input type="number" class="form-control" name="quantityss[]" placeholder="Enter Quantity" value="">
                                                                </div>
                                                                <div class="col-md-4 mt-1">
                                                                <input type="number" class="form-control" name="pricess[]" placeholder="Enter Price" value="0">
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                        @endif
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                            </div>
                                        </div>
                                        <div id="onlycolors" class="col-lg-12 mt-3">
                                            <table class="table table-stripped" id="officers-table3">
                                                <tbody >
                                                <tr id="new3" style="margin-top:5px;">
                                                    <td>
                                                        <div class="row">
                                                            <div class="col-md-3">
                                                                Color
                                                            </div>
                                                            <div class="col-md-3">
                                                                Quantity
                                                            </div>
                                                            <div class="col-md-3">
                                                                Additional Price
                                                            </div>
                                                        </div>
                                                            <div class="row" style="margin-top:5px;">
                                                                <div class="col-md-3">
                                                                    <select name="colors[]" id="color" class="form-control" step="any">
                                                                        <option> Select Color</option>
                                                                        <?php
                                                                            $colorsss=DB::table('colors')->where('store_id',$store_id)->get();
                                                                        ?>
                                                                        @if(isset($colorsss))
                                                                        @foreach($colorsss as $cl)
                                                                            <option value="{{$cl->code}}">{{$cl->name}}</option>
                                                                        @endforeach
                                                                        @endif
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <input type="number" class="form-control" name="quantitysss[]" placeholder="Enter Quantity" min="0" value="">
                                                                </div>
                                                                <div class="col-md-3">
                                                                <input type="number" class="form-control" name="pricesss[]" placeholder="Enter Price" min="0" value="0">
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <a class="remove-officer-button3 mt-3" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete"><img src="{{URL::to('/')}}/img/delete.png" alt="" width="30px" style="margin-bottom:5px;"></a>
                                                                        <br>
                                                                    <a class="" onclick="addOnlycolor()" data-bs-toggle="tooltip" data-bs-placement="top" title="Add"><img src="{{URL::to('/')}}/img/add.png" alt="" width="30px"></a>
                                                                </div>
                                                            </div>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div id="unittss" class="col-lg-12 mt-3">
                                            <div class="table-responsive">
                                            <table class="table table-stripped" id="officers-table1">
                                                <tbody >
                                                    <?php $i=0; ?>
                                                <tr id="new1" style="margin-top:5px;">
                                                    </td>
                                                    <td class="mt-1">
                                                        <div class="row">
                                                            <div class="col-md-3 mt-1">
                                                                Volume
                                                            </div>
                                                            <div class="col-md-3 mt-1">
                                                                Unit
                                                            </div>
                                                            <div class="col-md-3 mt-1">
                                                                Quantity
                                                            </div>
                                                            <div class="col-md-3 mt-1">
                                                                Additional Price
                                                            </div>
                                                        </div>
                                                        <div class="row" style="margin-top:5px;">
                                                            <div class="col-md-3 mt-1">
                                                                <input type="number" class="form-control" name="volume[]" value="">
                                                            </div>
                                                            <div class="col-md-3 mt-1">
                                                                <select name="unit[]" id="color" class="form-control" step="any">
                                                                    <option> Select Unit</option>
                                                                    <?php
                                                                    $color=DB::table('units')->where('store_id',$store_id)->get();

                                                                    ?>
                                                                    @if(isset($color))
                                                                    @foreach($color as $cl)
                                                                        <option value="{{$cl->name}}">{{$cl->name}}</option>
                                                                    @endforeach
                                                                    @endif
                                                                </select>
                                                            </div>
                                                            <div class="col-md-3 mt-1">
                                                                <input type="number" class="form-control" name="quantityssss[]" placeholder="Enter Quantity" value="">
                                                            </div>
                                                            <div class="col-md-3 mt-1">
                                                            <input type="number" class="form-control" name="pricessss[]" placeholder="Enter Price" value="0">
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="mt-1">
                                                        <a class="remove-officer-button1  mt-3" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete"><img src="{{URL::to('/')}}/img/delete.png" alt="" width="30px" style="margin-bottom:5px;"></a>
                                                    <br>
                                                        <a onclick="addUnit()" data-bs-toggle="tooltip" data-bs-placement="top" title="Add"><img src="{{URL::to('/')}}/img/add.png" alt="" width="30px"></a>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                            </div>
                                        </div>
                                    </div>

                                <!-- </form> -->
                            </div>
                        </div> <!-- card end// -->
                        <button class="btn btn-info rounded font-sm hover-up" type="submit">Publish</button>
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
<script src="{{asset('admin/src/bootstrap-tagsinput.js')}}"></script>
<script src="{{asset('admin/src/bootstrap-tagsinput-angular.js')}}"></script>
 <script>

// var citynames = new Bloodhound({
//   datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
//   queryTokenizer: Bloodhound.tokenizers.whitespace,
//   prefetch: {
//     url: 'assets/citynames.json',
//     filter: function(list) {
//       return $.map(list, function(cityname) {
//         return { name: cityname }; });
//     }
//   }
// });
// citynames.initialize();

// $('input').tagsinput({
//     debugger;
//   typeaheadjs: {
//     name: 'citynames',
//     displayKey: 'name',
//     valueKey: 'name',
//     source: citynames.ttAdapter()
//   }
// });
// </script>
// <script>

// var citynames = new Bloodhound({
//   datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
//   queryTokenizer: Bloodhound.tokenizers.whitespace,
//   prefetch: {
//     url: 'assets/citynames.json',
//     filter: function(list) {
//       return $.map(list, function(cityname) {
//         return { name: cityname }; });
//     }
//   }
// });
// citynames.initialize();

// $('input').seoinput({
//   typeaheadjs: {
//     name: 'citynames',
//     displayKey: 'name',
//     valueKey: 'name',
//     source: citynames.ttAdapter()
//   }
// });
 </script>
<script>
    $(document).ready(function(){
        $('#colorrss').hide();
        $('#unittss').hide();
        $('#sizess').hide();
        $('#shiphide').hide();
        $('#shipping-div').hide();
        $('#attrihide').hide();
        $('#attri-div').hide();
        $('#onlycolors').hide();
        $('#attributes').on('change', function() {
            var l=this.value;
          if(l=='none'){
              $('#colorrss').hide();
              $('#unittss').hide();
              $('#sizess').hide();
              $('#onlycolors').hide();
          }else if(l=='color'){
              $('#colorrss').show();
              $('#unittss').hide();
              $('#sizess').hide();
              $('#onlycolors').hide();
          }else if(l=='unit'){
              $('#colorrss').hide();
              $('#unittss').show();
              $('#sizess').hide();
              $('#onlycolors').hide();
          }else if(l=='onlycolor'){
              $('#colorrss').hide();
              $('#unittss').hide();
              $('#sizess').hide();
              $('#onlycolors').show();
          }else{
              $('#colorrss').hide();
              $('#unittss').hide();
              $('#sizess').show();
              $('#onlycolors').hide();
          }
        });
        $('#shipshow').on('click',function(){
            $('#shipping-div').show();
            $('#shiphide').show();
            $('#shipshow').hide();
        });
        $('#shiphide').on('click',function(){
            $('#shipping-div').hide();
            $('#shiphide').hide();
            $('#shipshow').show();
        });
        $('#attrishow').on('click',function(){
            $('#attri-div').show();
            $('#attrihide').show();
            $('#attrishow').hide();
        });
        $('#attrihide').on('click',function(){
            $('#attri-div').hide();
            $('#attrihide').hide();
            $('#attrishow').show();
        });
    })
</script>
<script>
$(document).ready(function() {

	$('input[name="input"]').tagsinput({
		trimValue: true,
		confirmKeys: [13, 44, 32],
		focusClass: 'my-focus-class'
	});

	$('.bootstrap-tagsinput input').on('focus', function() {
		$(this).closest('.bootstrap-tagsinput').addClass('has-focus');
	}).on('blur', function() {
		$(this).closest('.bootstrap-tagsinput').removeClass('has-focus');
	});

});


    function addRows(){
        var col=$('#new').html();
        $("table tbody").append('<tr>'+col+'</tr>');
    }
function addRow(){
        var colors = {!! json_encode($colors, JSON_HEX_TAG) !!};
        color = [];
        colors.forEach(function (data){
          color += ` <option value="`+data.name+`">`+data.name+`</option>`
        });
        console.log(color);
        var sizes = {!! json_encode($size, JSON_HEX_TAG) !!};
        size = [];
        index = document.getElementById('index').value;

        i = document.getElementById('index').value = index + 1;
        var j=0;
        sizes.forEach(function (data){
            console.log(data.name);
          size += ` <div class="row" style="margin-top:5px;">
                                                            <div class="col-md-1">
                                                                <input type="checkbox"  name="sid[`+i+`][`+j+`]" value="`+data.id+`">
                                                            </div>
                                                            <div class="col-md-3">
                                                                <input type="text" class="form-control" name="size[`+i+`][`+j+`]" value="`+data.name+`" readonly>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <input type="number" class="form-control" name="quantitys[`+i+`][`+j+`]" placeholder="Enter Quantity" value="">
                                                            </div>
                                                            <div class="col-md-4">
                                                            <input type="number" class="form-control" name="price[`+i+`][`+j+`]" placeholder="Enter Price" value="0">
                                                            </div>
                                                        </div>` ;
                                                        j++;

        });
        i++;
        console.log(size);
        index = document.getElementById('index').value;

        addindex = document.getElementById('index').value = index + 1;

        var col=`<tr id="new" style="margin-top:5px;">

                                                <td>
                                                <label>Color:</label>
                                                    <select name="color[`+addindex+`][]" id="color" class="form-control" step="any">
                                                        <option> Select Color</option>
                                                        `+color+`
                                                    </select>
                                                </td>
                                                <td>

                                                </td>
                                                <td>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            size
                                                        </div>
                                                        <div class="col-md-4">
                                                            Quantity
                                                        </div>
                                                        <div class="col-md-4">
                                                            Price
                                                        </div>
                                                    </div>

                                                  `+size+`
                                                </td>
                                                <td>
                                                    <a class="remove-officer-button mt-3" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete"><img src="{{URL::to('/')}}/img/delete.png" alt="" width="30px" style="margin-bottom:5px;"></a>
                                                    <br>
                                                    <a onclick="addRow()" data-bs-toggle="tooltip" data-bs-placement="top" title="Add"><img src="{{URL::to('/')}}/img/add.png" alt="" width="30px"></a>
                                                </td>
                                            </tr>`
        $("#officers-table tbody").append(col);

}
$("#officers-table").on('click', '.remove-officer-button', function(e) {
    var whichtr = $(this).closest("tr");

    // alert('worked'); // Alert does not work
    whichtr.remove();
});
$("#officers-table1").on('click', '.remove-officer-button1', function(e) {
    var whichtr = $(this).closest("tr");

    // alert('worked'); // Alert does not work
    whichtr.remove();
});
function addUnit(){
        var col=$('#new1').html();
        $("#officers-table1 tbody").append('<tr>'+col+'</tr>');
}
function addSize(){
        var col=$('#new2').html();
        $("#officers-table2 tbody").append('<tr>'+col+'</tr>');
}
function addOnlycolor(){
        var col=$('#new3').html();
        $("#officers-table3 tbody").append('<tr>'+col+'</tr>');
}
$("#officers-table2").on('click', '.remove-officer-button2', function(e) {
    var whichtr = $(this).closest("tr");

    // alert('worked'); // Alert does not work
    whichtr.remove();
});
$("#officers-table3").on('click', '.remove-officer-button3', function(e) {
    var whichtr = $(this).closest("tr");

    // alert('worked'); // Alert does not work
    whichtr.remove();
});
</script>
<script>

jQuery('select[name="category"]').on('change',function(){
        debugger;
        var val = $(this).val();
        console.log(val);
        $('#subcategory').empty();
        var catid=$('select[name="category"]').val();
        $.get('/getsubcat',{catid:catid},function(data){
            console.log(data);
            for (var i = 0; i <data.length; i++) {
            $('#subcategory').append(

                '<option value="'+data[i].id+'">'+data[i].name+'</option>'
                );
            }
        });
    });
</script>
@endpush
