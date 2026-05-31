@extends('admin.layouts.main')
@section('content')
<style>
    .chatbox2{
        width: 100%;
        height: 550px;
        /*position: fixed;*/
        /*bottom: 90px;*/
        right: 0;
    }
    .chatbox2 .receive{
        display:flex;
        align-items: flex-start;
        width:fit-content;
        border:1px solid gray;
        border-radius:20px;
        background-color:gray;
        color:#fff;
    }
    .chatbox2 .send{
        display:flex;
        justify-content: flex-end;
        width: 100%;
        flex-direction: column;
        align-items: end;
    }
    .chatbox2 .send p{
        border-radius:20px;
        background-color:green;
        color:#fff;
        width:fit-content;
        padding:10px;
    }
</style>
<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
<div class="container-fluid navbars" style="background-image: linear-gradient(53deg, #42424a 0%, #191919 100%);">
    <div class="row">
        <div class="col-md-12">
            <nav style="--bs-breadcrumb-divider: '';" aria-label="breadcrumb">
                <ol class="breadcrumb" style="background-color:transparent;color:#fff">
                    <li class="breadcrumb-item">
                        <a href="{{URL::to('/')}}/superadmin/planorderrequest">
                            <img src="{{URL::to('/')}}/img/cubes.png"> <br> Plan Order Request
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{URL::to('/')}}/superadmin/planorderrequest/rejected">
                            <img src="{{URL::to('/')}}/img/cubes.png"> <br> Rejected Plan Order
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{URL::to('/')}}/superadmin/planorderrequest-today">
                            <img src="{{URL::to('/')}}/img/cubes.png"> <br>Today Plan Order
                        </a>
                    </li>
                    <li class="breadcrumb-item ">
                        <a href="{{URL::to('/')}}/superadmin/invoiceorder">
                            <img src="{{URL::to('/')}}/img/cubes.png"> <br>Invoice Order Request
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{URL::to('/')}}/superadmin/allinvoiceorder">
                            <img src="{{URL::to('/')}}/img/cubes.png"> <br>Invoice Order
                        </a>
                    </li>
                    <li class="breadcrumb-item active">
                        <a href="{{route('superadmin.customizerequest')}}">
                            <img src="{{URL::to('/')}}/img/cubes.png"> <br>Customize Request
                        </a>
                    </li>
                
                </ol>
            </nav>
        </div>
    </div>
</div>

<div class="container-fluid mt-4" id="toplist">
    <div class="row">
        <div class="col-md-6">
            <h4>Chat</h4>
        </div>
        <div class="col-md-6">
            <ul>
                <li style="padding:0px;border:0px;"><a href="{{route('superadmin.customizerequest')}}" class="btn btn-primary" style="display:block;border-radius:0px !important">Back</a></li>
            </ul>
        </div>
    </div>
    <div class="row mt-5 productlist">
        <div class="col-12">
        <div class="card chatbox2">
            <div class="card-header" style="background-color:black">
                <div class="row">
                    <?php
                    $store=DB::table('stores')->where('id',$data->store_id)->first();
                    ?>
                    <div class="col-md-6" style="color:#fff">Store : {{$store->name}}</div>
                </div>
            </div>
            <div class="card-body" id="messagetoken" style="height:100px;overflow-y:auto">
            @if (Session::has('success_message'))
                    <div class="alert alert-success">{{Session::get('success_message')}}</div>
                @endif
                <ul style="list-style: none;padding-left: 0px;display: flex;flex-direction: column;overflow-y: auto;">
                @if(isset($messages) && count($messages)>0)
                @foreach($messages as $msg)
                    @if($msg->sender=='superadmin')
                        @if(isset($msg->image))
                        <li class="send" style="border:0px !important">
                            <p style="background-color:transparent !important;border:0px !important">
                                <img src="{{URL::to('/')}}/assets/images/token/{{$msg->image}}" width="100">
                            </p>
                            <span>{{$msg->created_at}}</span>
                        </li>
                        @endif
                        @if(isset($msg->message))
                        <li class="send" style="border:0px !important">
                            <p>{{$msg->message}}</p>
                            <span>{{$msg->created_at}}</span>
                        </li>
                        
                        @endif
                    @else
                        @if(isset($msg->image))
                        <li class="receive" style="background-color:transparent !important;border:0px !important">
                            <p style="background-color:transparent !important;border:0px !important">
                                <img src="{{URL::to('/')}}/assets/images/token/{{$msg->image}}" width="100">
                            </p>
                        </li>
                        <span>{{$msg->created_at}}</span>
                        @endif
                        @if(isset($msg->message))
                        <li class="receive">
                            {{$msg->message}}
                            
                            </li>
                            <span style="padding:2px 10px">{{$msg->created_at}}</span>
                        @endif
                    @endif
                @endforeach
                @endif
                </ul>
                    <div class="d-flex mt-4 mb-4 justify-content-center">
                    </div>
            </div>
            <div class="card-footer" style="border-top:1px solid gray">
                <div class="row">
                    <div class="col-1">
                        <form action="{{route('superadmin.sendmessage.token',$data->token)}}" method="post" enctype="multipart/form-data">
                            @csrf
                        <label for="inputimg">
                            <img id="blah" alt="Insert Image" style="width:100px;height:80px" src="https://img.icons8.com/dotty/80/000000/add-image.png" />
                        </label>
                        <input type="file" 
                            onchange="document.getElementById('blah').src = window.URL.createObjectURL(this.files[0])" id="inputimg" name="image" style="display:none">
                    </div>
                    <div class="col-10">
                        <textarea id="text" name="details" class="form-control"></textarea>
                    </div>
                    <div class="col-1">
                        
                        <button type="submit" class="btn btn-primary">Send</button>
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
@push('scripts')
<script>
    $(document).ready(function(){
        const element = document.getElementById('messagetoken');
        element.scrollTop = element.scrollHeight;
      $("#taskfilter").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("#taskfilterresult tbody tr").filter(function() {
          $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
      });
   
    });
   function exportTasks(_this) {
      let _url = $(_this).data('href');
      window.location.href = _url;
   }
</script>
@endpush