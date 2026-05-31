@extends('admin.layouts.main')
@section('content')
<style>
    .productlist .card-body .table td {
  text-align: left !important;
}
</style>
<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
    <div class="container-fluid navbars" style="background-image: linear-gradient(53deg, #42424a 0%, #191919 100%);">
        <div class="row">
            <div class="col-md-12">
                <nav style="--bs-breadcrumb-divider: '';" aria-label="breadcrumb">
                    <ol class="breadcrumb" style="background-color:transparent;color:#fff">
                        <li class="breadcrumb-item">
                            <a href="{{ route('superadmin.store.manage') }}">
                                <img src="{{URL::to('/')}}/img/icons/box.png"> <br> <span class="nav-link-text ms-1">@if(Session::has('lang') && Session::get('lang')=='bn') সব দোকান @else All Store @endif</span>
                            </a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="#{{URL::to('/')}}/products">
                                <img src="{{URL::to('/')}}/img/icons/box.png"> <br> <span class="nav-link-text ms-1">@if(Session::has('lang') && Session::get('lang')=='bn') পণ্য @else Products @endif</span>
                            </a>
                        </li>
                        @if(Auth::user()->type=="superadmin" || Auth::user()->type=="superadminstaff")
                        <li class="breadcrumb-item active" aria-current="page">
                            <a href="{{ route('superadmin.store.category') }}">
                                <img src="{{URL::to('/')}}/img/icons/categories.png" > <br><span class="nav-link-text ms-1">@if(Session::has('lang') && Session::get('lang')=='bn') ক্যাটাগরি @else Categories @endif</span>
                            </a>
                        </li>
                        @endif
                        @if(Auth::user()->type=="superadmin" || Auth::user()->type=="superadminstaff")
                        <li class="breadcrumb-item" aria-current="page">
                            <a href="#{{route('admin.subcategory.index')}}">
                                <img src="{{URL::to('/')}}/img/subcategory.png" > <br><span class="nav-link-text ms-1">@if(Session::has('lang') && Session::get('lang')=='bn') সাব ক্যাটাগরি @else Sub Categories @endif</span>
                            </a>
                        </li>
                        @endif
                        @if(Auth::user()->type=="superadmin" || Auth::user()->type=="superadminstaff")
                        <li class="breadcrumb-item" aria-current="page">
                            <a href="#{{URL::to('/')}}/attribute">
                                <img src="{{URL::to('/')}}/img/icons/product.png" ><br><span class="nav-link-text ms-1">@if(Session::has('lang') && Session::get('lang')=='bn') পণ্যের ধরণ @else Variants @endif</span>

                            </a>
                        </li>
                        @endif
                        @if(Auth::user()->type=="superadmin" || Auth::user()->type=="superadminstaff")
                        <li class="breadcrumb-item" aria-current="page">
                            <a href="#{{URL::to('/')}}/brand">
                                <img src="{{URL::to('/')}}/img/icons/brand.png" > <br><span class="nav-link-text ms-1">@if(Session::has('lang') && Session::get('lang')=='bn') ব্রান্ড @else Brands @endif</span>
                            </a>
                        </li>
                        @endif

                        @if(Auth::user()->type=="superadmin" || Auth::user()->type=="superadminstaff")
                        <li class="breadcrumb-item" aria-current="page">
                            <a href="#{{URL::to('/')}}/supplier">
                                <img src="{{URL::to('/')}}/img/icons/supplier.png" > <br><span class="nav-link-text ms-1">@if(Session::has('lang') && Session::get('lang')=='bn') সরবরাহকারী@else Suppliers @endif</span>
                            </a>
                        </li>
                        @endif
                    </ol>
                </nav>
            </div>
        </div>
    </div>
<div class="container-fluid mt-4" id="toplist">
    <div class="row">
        <div class="col-md-6">
            <h4>Add Categories</h4>
        </div>
        <div class="col-md-6">
            <ul>
                <li class="active"><a href="javascript:void(0)">Create New</a></li>
                <li><a href="">Import</a></li>
                <li><a href="">Export</a></li>
            </ul>
        </div>
    </div>
    <div class="row mt-5 productlist">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <form action="{{ route('superadmin.store.category.catAddStore') }}" method="post" enctype="multipart/form-data">
                    @csrf
            <div class="card">
                <div class="card-header">
                    <h4 style="text-align: center;">
                        <span style="float: left;">All Categories </span>
                        <input style="width: 25%; display: inline-table;" id="search_field" type="text" class="form-control" placeholder="Searching Category">
                        <button style="float: right;" type="submit" class="btn btn-info">Update</button></h4>

                </div>
                <div class="card-body">

                    <div class="mb-3 row">
                        <input type="hidden" name="market_cat_id" value="{{ $id }}">
                        @foreach ($catgories as $category)

                        <div id="categorySearch" class="col-md-3">
                            <input type="hidden" id="d_{{ $category->id }}" name="" value="">
                            <input type="checkbox" id="l_{{ $category->id }}" onclick="clickByCategory({{ $category->id }})" name="categoryId[]" value="{{ $category->id }}" {{ $category->market_id == $id ? 'checked': '' }}>
                            <label id="cateSearch" for="l_{{ $category->id }}">{{ $category->name }}</label>
                        </div>
                        @endforeach
                    </div>
                    {{-- <div class="mb-3 row">
                        <label for="position" class="col-md-3 col-form-label"></label>
                        <div class="col-md-8" style="text-align:right">
                            <button type="submit" class="btn btn-info">Update</button>
                        </div>
                    </div> --}}

                </div>
            </div>
         </form>
        </div>
    </div>
</div>
</main>
@endsection

@push('scripts')

<script>
    function clickByCategory(id){
        let tmp = $('#l_'+id).is(':checked');
        if (tmp) {
            $("#l_"+id).val(id);
            $("#d_"+id).val('');
            $("#d_"+id).attr('name', '');
        } else {
            $("#l_"+id).val('');
            $("#d_"+id).val(id);
            $("#d_"+id).attr('name', 'DeAccategoryId[]');
        }
    }
</script>

<script>
    // SEARCH FUNCTION
    var btsearch = {
        init: function(search_field, searchable_elements, searchable_text_class) {
            $(search_field).keyup(function(e){
                e.preventDefault();
                var query = $(this).val().toLowerCase();

                if(query){
                    // loop through all elements to find match
                    $.each($(searchable_elements), function(){
                        var title = $(this).find(searchable_text_class).text().toLowerCase();
                        if(title.indexOf(query) == -1){
                            $(this).hide();
                        } else {
                            $(this).show();
                        }
                    });
                } else {
                    // empty query so show everything
                    $(searchable_elements).show();
                }
            });
        }
    }

    // INIT
    $(function(){
    // USAGE: btsearch.init(('search field element', 'searchable children elements', 'searchable text class');
    btsearch.init('#search_field', '.row #categorySearch', '#cateSearch');
    });
</script>


<script>
    $(document).ready(function(){
      $("#taskfilter").on("keyup", function() {
          debugger;
        var value = $(this).val().toLowerCase();
        debugger;
        $("#taskfilterresult tbody tr").filter(function() {
            debugger;
          $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
          debugger;
        });
      });
    });
    </script>
@endpush
