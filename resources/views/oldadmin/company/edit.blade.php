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
                    <li class="breadcrumb-item active">
                        <a href="{{route('admin.company')}}">
                            <img src="{{URL::to('/')}}/img/cubes.png"> <br> Company
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="#">
                            <img src="{{URL::to('/')}}/img/cubes.png"> <br> Billing
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="#">
                            <img src="{{URL::to('/')}}/img/cubes.png"> <br> Profile
                        </a>
                    </li>
                </ol>
            </nav>
        </div>
    </div>
</div>
    <section class="container content-main">
            <div class="row">
            <form action="{{route('admin.updatecompany',$singleData->id)}}" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="index" value="1" id="index">
                            @csrf
                <div class="row">
                <div class="col-lg-9 mt-4 mb-4">
                    <div class="content-header row">
                        <div class="col-md-6">
                            <h2 class="content-title">Edit Company</h2>
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
                                    <label for="product_name" class="form-label">Name</label>
                                    <div class="col-md-8">
                                    <input type="text" placeholder="Type here" class="form-control" id="name" name="name" value="{{$singleData->name}}">
                                    @error('name')
                                            <p class="text-danger" role="alert">{{$message}}</p>
                                    @enderror
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label for="product_name" class="form-label">Email</label>
                                    <div class="col-md-8">
                                    <input type="email" placeholder="Type here" class="form-control" id="email" name="email" value="{{$singleData->email}}">
                                    @error('email')
                                            <p class="text-danger" role="alert">{{$message}}</p>
                                    @enderror
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label for="product_name" class="form-label">Phone</label>
                                    <div class="col-md-8">
                                    <input type="number" placeholder="Type here" class="form-control" id="phone" name="phone" value="{{$singleData->phone}}">
                                    @error('phone')
                                            <p class="text-danger" role="alert">{{$message}}</p>
                                    @enderror
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label for="product_name" class="form-label">Active Plan</label>
                                    <div class="col-md-8">
                                    <input type="text" placeholder="Type here" class="form-control" id="activeplan" name="activeplan"  value="{{$singleData->activeplan}}">
                                    @error('activeplan')
                                            <p class="text-danger" role="alert">{{$message}}</p>
                                    @enderror
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-info mt-4 ml-3">Update</button>
                            
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
