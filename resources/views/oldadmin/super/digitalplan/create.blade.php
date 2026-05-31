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
<div class="container-fluid navbars" style="background-image: linear-gradient(53deg, #42424a 0%, #191919 100%);">
    <div class="row new">
        <div class="col-md-12">
            <nav style="--bs-breadcrumb-divider: '';" aria-label="breadcrumb">
                <ol class="breadcrumb" style="background-color:transparent;color:#fff">
                    <li class="breadcrumb-item">
                        <a href="{{route('plans')}}">
                            <img src="{{URL::to('/')}}/img/cubes.png"> <br> Website Plans
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{route('posplans')}}">
                            <img src="{{URL::to('/')}}/img/cubes.png"> <br> Pos Plans
                        </a>
                    </li>
                    <li class="breadcrumb-item active">
                        <a href="{{route('digitalplans')}}">
                            <img src="{{URL::to('/')}}/img/cubes.png"> <br> Digital Plans
                        </a>
                    </li>
                </ol>
            </nav>
        </div>
    </div>
</div>
    <section class="container content-main">
            <div class="row">
            <form action="{{route('savedigitalplan')}}" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="index" value="1" id="index">
                            @csrf
                <div class="row">
                <div class="col-lg-9 mt-4 mb-4">
                    <div class="content-header row">
                        <div class="col-md-6">
                            <h2 class="content-title">Add New Digital Plan</h2>
                        </div>
                        
                        <div class="col-md-6" style="text-align:right">
                            <!-- <button class="btn btn-light rounded font-sm mr-5 text-body hover-up">Save to draft</button> -->
                            <!-- <button class="btn btn-info rounded font-sm hover-up">Publich</button> -->
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-8">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h4>Basic</h4>
                        </div>
                        <div class="card-body">
                        
                                <div class="row mb-4">
                                    <label for="product_name" class="form-label">Plan Name</label>
                                    <div class="col-md-8">
                                    <input type="text" placeholder="Type here" class="form-control" id="name" name="name">
                                    @error('name')
                                            <p class="text-danger" role="alert">{{$message}}</p>
                                    @enderror
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label for="product_name" class="form-label">Plan Subtitle</label>
                                    <div class="col-md-8">
                                    <input type="text" placeholder="Type here" class="form-control" id="name" name="subtitle">
                                    @error('subtitle')
                                            <p class="text-danger" role="alert">{{$message}}</p>
                                    @enderror
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label class="form-label">Price</label>
                                    <div class="col-md-8">
                                    <input type="number" name="price" id="price" class="form-control" placeholder="per month">
                                    @error('price')
                                            <p class="text-danger" role="alert">{{$message}}</p>
                                    @enderror
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label class="form-label">Discount Type</label>
                                    <div class="col-md-8">
                                    <select class="form-control" name="discount_type" id="discount_type">
                                        <option value="no_discount">No Discount</option>
                                        <option value="percent">Percent</option>
                                        <option value="fixed">Fixed</option>
                                    </select>
                                    @error('discount_type')
                                            <p class="text-danger" role="alert">{{$message}}</p>
                                    @enderror
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label class="form-label">1st Month Discount</label>
                                    <div class="col-md-8">
                                    <input type="number" name="onemdis" id="onemdis" class="form-control">
                                    @error('onemdis')
                                            <p class="text-danger" role="alert">{{$message}}</p>
                                    @enderror
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label class="form-label">6 Month Discount</label>
                                    <div class="col-md-8">
                                    <input type="number" name="sixstmdis" id="sixstmdis" class="form-control">
                                    @error('sixstmdis')
                                            <p class="text-danger" role="alert">{{$message}}</p>
                                    @enderror
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label class="form-label">12 Month Discount</label>
                                    <div class="col-md-8">
                                    <input type="number" name="twelvestmdis" id="twelvestmdis" class="form-control">
                                    @error('twelvestmdis')
                                            <p class="text-danger" role="alert">{{$message}}</p>
                                    @enderror
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label class="form-label">24 Month Discount</label>
                                    <div class="col-md-8">
                                    <input type="number" name="twentyfourstmdis" id="twentyfourstmdis" class="form-control">
                                    @error('twentyfourstmdis')
                                            <p class="text-danger" role="alert">{{$message}}</p>
                                    @enderror
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label class="form-label">Page Setup</label>
                                    <div class="col-md-8">
                                    <select class="form-control" name="page_setup" id="page_setup">
                                        <option value="Yes">Yes</option>
                                        <option value="No">No</option>
                                    </select>
                                    @error('page_setup')
                                            <p class="text-danger" role="alert">{{$message}}</p>
                                    @enderror
                                    </div>
                                </div>
                                <!--<div class="row mb-4">-->
                                <!--    <label class="form-label">Branch</label>-->
                                <!--    <div class="col-md-8">-->
                                <!--    <input type="number" name="branch" id="branch" class="form-control" value="0">-->
                                <!--    @error('branch')-->
                                <!--            <p class="text-danger" role="alert">{{$message}}</p>-->
                                <!--    @enderror-->
                                <!--    </div>-->
                                <!--</div>-->
                                <div class="row mb-4">
                                    <label class="form-label">Static Content</label>
                                    <div class="col-md-8">
                                    <input type="number" name="static_content" id="static_content" class="form-control" value="0">
                                    @error('static_content')
                                            <p class="text-danger" role="alert">{{$message}}</p>
                                    @enderror
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label class="form-label">Video Content</label>
                                    <div class="col-md-8">
                                    <input type="number" name="video_content" id="video_content" class="form-control" value="0">
                                    @error('video_content')
                                            <p class="text-danger" role="alert">{{$message}}</p>
                                    @enderror
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label class="form-label">Gify Content</label>
                                    <div class="col-md-8">
                                    <input type="number" name="gify_content" id="gify_content" class="form-control" value="0">
                                    @error('gify_content')
                                            <p class="text-danger" role="alert">{{$message}}</p>
                                    @enderror
                                    </div>
                                </div>
                                <!--<div class="row mb-4">-->
                                <!--    <label class="form-label">Product</label>-->
                                <!--    <div class="col-md-8">-->
                                <!--    <input type="number" name="product" id="product" class="form-control" value="0">-->
                                <!--    @error('product')-->
                                <!--            <p class="text-danger" role="alert">{{$message}}</p>-->
                                <!--    @enderror-->
                                <!--    </div>-->
                                <!--</div>-->
                                <!--<div class="row mb-4">-->
                                <!--    <label class="form-label">Inventory</label>-->
                                <!--    <div class="col-md-8">-->
                                <!--    <select class="form-control" name="inventory" id="inventory">-->
                                <!--        <option value="1">Yes</option>-->
                                <!--        <option value="0">No</option>-->
                                <!--    </select>-->
                                <!--    @error('inventory')-->
                                <!--            <p class="text-danger" role="alert">{{$message}}</p>-->
                                <!--    @enderror-->
                                <!--    </div>-->
                                <!--</div>-->
                                <div class="row mb-4">
                                    <label class="form-label">Google Ad</label>
                                    <div class="col-md-8">
                                    <select class="form-control" name="googlead" id="googlead">
                                        <option value="Yes">Yes</option>
                                        <option value="No">No</option>
                                    </select>
                                    @error('googlead')
                                            <p class="text-danger" role="alert">{{$message}}</p>
                                    @enderror
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label class="form-label">Boosting Page</label>
                                    <div class="col-md-8">
                                    <select class="form-control" name="boosting_page" id="boosting_page">
                                        <option value="Yes">Yes</option>
                                        <option value="No">No</option>
                                    </select>
                                    @error('boosting_page')
                                            <p class="text-danger" role="alert">{{$message}}</p>
                                    @enderror
                                    </div>
                                </div>
                                <!--<div class="row mb-4">-->
                                <!--    <label class="form-label">Website Setup</label>-->
                                <!--    <div class="col-md-8">-->
                                <!--    <select class="form-control" name="website_setup" id="website_setup">-->
                                <!--        <option value="1">Yes</option>-->
                                <!--        <option value="0">No</option>-->
                                <!--    </select>-->
                                <!--    @error('website_setup')-->
                                <!--            <p class="text-danger" role="alert">{{$message}}</p>-->
                                <!--    @enderror-->
                                <!--    </div>-->
                                <!--</div>-->
                                <div class="row mb-4">
                                    <label class="form-label">Caption Writting</label>
                                    <div class="col-md-8">
                                    <input type="number" name="caption_writting" id="caption_writting" class="form-control" value="0">
                                    @error('caption_writting')
                                            <p class="text-danger" role="alert">{{$message}}</p>
                                    @enderror
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <div class="col-lg-8">
                                        <div class="mb-4">
                                            <label class="form-label">Position</label>
                                            <div class="row gx-2">
                                                <input placeholder="0" type="number" class="form-control" name="position">
                                                @error('position')
                                                    <p class="text-danger" role="alert">{{$message}}</p>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <br>
                                    <div class="col-lg-8">
                                        <div class="row">
                                            <label for="staticEmail" class="col-md-2 col-form-label">Status</label>
                                            <div class="col-md-4">
                                            <div class="form-check form-switch is-filled" style="text-align:center;padding-top:14px;">
                                                <input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked" name="status" style="margin:0 auto;" checked="">
                                                <label class="form-check-label" for="flexSwitchCheckChecked"></label>
                                            </div>
                                            @error('status')
                                                    <p class="text-danger" role="alert">{{$message}}</p>
                                                @enderror
                                            </div>
                                            </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-info mt-4 ml-3">Submit</button>
                            
                        </div>
                    </div> <!-- card end// -->
                    
                </div>
                </div>
                
</form>
            </div>
        </section>
    </div>
</main>
@endsection

@push('scripts')
    
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
                
                '<option value="">select</option><option value="'+data[i].id+'">'+data[i].name+'</option>'
                );
            }
        });
    });
</script>
@endpush
