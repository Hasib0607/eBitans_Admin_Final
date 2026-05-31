@extends('admin.layouts.main')
@section('content')
<main class="main-content position-relative border-radius-lg">
<div class="container-fluid navbars" style="background-image: linear-gradient(53deg, #42424a 0%, #191919 100%);">
    <div class="row new">
        <div class="col-md-12">
            <nav style="--bs-breadcrumb-divider: '';" aria-label="breadcrumb">
                <ol class="breadcrumb" style="background-color:transparent;color:#fff">
                    @if(isset($template) && $template=='1' || Auth::user()->type=='admin')
                    <li class="breadcrumb-item" aria-current="page">
                        <a href="{{route('admin.design.theme')}}">
                            <img src="{{URL::to('/')}}/img/icons/web-design.png" > <br><span class="nav-link-text ms-1">@if(Session::has('lang') && Session::get('lang')=='bn') ওয়েবসাইট থিম @else Website Themes @endif</span>
                        </a>
                    </li>

                    @if(isset($homepage) && $homepage=='1' || Auth::user()->type=='admin')
                    <li class="breadcrumb-item" aria-current="page">
                        <a href="{{route('admin.design.homepage.slider')}}">
                            <img src="{{URL::to('/')}}/img/icons/landing-page.png" > <br><span class="nav-link-text ms-1">@if(Session::has('lang') && Session::get('lang')=='bn') হোম পেজ ডিজাইন @else HP Layout Design @endif</span>
                        </a>
                    </li>
                    @endif

                    @if(isset($header) && $header=='1' || Auth::user()->type=='admin')
                    <li class="breadcrumb-item" aria-current="page">
                        <a href="{{route('admin.design.design')}}">
                            <img src="{{URL::to('/')}}/img/icons/title.png" ><br><span class="nav-link-text ms-1">@if(Session::has('lang') && Session::get('lang')=='bn') হেডার ডিজাইন @else Header Design @endif</span>

                        </a>
                    </li>
                    @endif

                    <li class="breadcrumb-item">
                        <a href="{{route('admin.design.slider')}}">
                            <img src="{{URL::to('/')}}/img/icons/slider.png"> <br> <span class="nav-link-text ms-1">@if(Session::has('lang') && Session::get('lang')=='bn') স্লাইডার @else Slider @endif</span>
                        </a>
                    </li>
                    @endif

                    @if(isset($banner) && $banner=='1' || Auth::user()->type=='admin')
                    <li class="breadcrumb-item" aria-current="page">
                        <a href="{{route('admin.design.banner')}}">
                            <img src="{{URL::to('/')}}/img/icons/ads.png" > <br><span class="nav-link-text ms-1">@if(Session::has('lang') && Session::get('lang')=='bn') বিজ্ঞাপন ব্যানার @else Ads Banner @endif</span>
                        </a>
                    </li>
                    @endif
                    <!--@if(isset($layout) && $layout=='1' || Auth::user()->type=='admin')-->
                    <!--<li class="breadcrumb-item" aria-current="page">-->
                    <!--    <a href="{{route('admin.design.layout.homepage')}}">-->
                    <!--        <img src="{{URL::to('/')}}/img/icons/subcategory.png" > <br>Invoice-->
                    <!--    </a>-->
                    <!--</li>-->
                    <!--@endif-->


                    @if(isset($tt) && $tt=='1' || Auth::user()->type=='admin')
                    <li class="breadcrumb-item" aria-current="page">
                        <a href="{{route('admin.testimonials')}}">
                            <img src="{{URL::to('/')}}/img/icons/testimonial.png" > <br><span class="nav-link-text ms-1">@if(Session::has('lang') && Session::get('lang')=='bn') প্রশংসাপত্র @else Testimonials @endif</span>
                        </a>
                    </li>
                    @endif

                    @if(isset($pages) && $pages=='1' || Auth::user()->type=='admin')
                    <li class="breadcrumb-item active" aria-current="page">
                        <a href="{{route('admin.pages')}}">
                            <img src="{{URL::to('/')}}/img/icons/team.png" > <br><span class="nav-link-text ms-1">@if(Session::has('lang') && Session::get('lang')=='bn') অন্যান্য পেইজ @else Other Pages @endif</span>
                        </a>
                    </li>
                    @endif
                     @if(Auth::user()->type=='admin')
                    <li class="breadcrumb-item" aria-current="page">
                        <a href="{{route('admin.design.homepage.invoice')}}">
                            <img src="{{URL::to('/')}}/img/icons/bill-2.png" > <br><span class="nav-link-text ms-1">@if(Session::has('lang') && Session::get('lang')=='bn') চালান টেমপ্লেট @else Invoice Template @endif</span>
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
            <h4>@if(Session::has('lang') && Session::get('lang')=='bn') সব পেইজ @else All Pages @endif  </h4>
        </div>
        <div class="col-md-6">
            <ul>
                <li style="padding:0px;border:0px;"><a href="{{route('admin.addpage')}}" class="btn btn-primary" style="display:block;border-radius:0px !important">@if(Session::has('lang') && Session::get('lang')=='bn') নতুন পেইজ যোগ করুন @else Add New Page @endif </a></li>
            
            </ul>
        </div>
    </div>
    <div class="row mt-5 productlist">
        <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-2" style="padding-right:1px;">
                        <form id="submitform" method="post" action="{{route('admin.changepagessstatus')}}">
                        @csrf
                        <input type="hidden" name="text2" id="selectids">
                        <select class='form-control' name="action" id="action">
                            <option value="select">@if(Session::has('lang') && Session::get('lang')=='bn') সিলেক্ট  অপসন @else Select Option @endif</option>
                            <option value="active">@if(Session::has('lang') && Session::get('lang')=='bn') সক্রিয় @else Active @endif</option>
                            <option value="deactive">@if(Session::has('lang') && Session::get('lang')=='bn') নিষ্ক্রিয় @else Deactive @endif</option>
                            <option value="delete">@if(Session::has('lang') && Session::get('lang')=='bn') ডিলিট @else Delete @endif</option>
                        </select>
                    </div>
                    <div class="col-md-1" style="padding-left:0px;">
                        <p id="submit" class="btn btn-primary filterbuttonss">@if(Session::has('lang') && Session::get('lang')=='bn') আবেদন  @else Apply @endif</p>
                        </form>
                    </div>
                    <div class="col-md-7"></div>
                    <div class="col-md-2">
                        <div class="input-group" >
                            <input type="text" class="form-control" aria-label="Dollar amount (with dot and two decimal places)" id="taskfilter">
                            <span class="input-group-text" style="padding: 0.75rem 11px !important;"><i class="fa fa-search"></i></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @if (Session::has('success_message'))
                    <div class="alert alert-success">{{Session::get('success_message')}}</div>
                @endif
                <div class="table-responsive" id="desktoptable">
                    <table class="table table-striped" id="taskfilterresult" width="100%">
                        <thead>
                            <tr>
                                <th width="4%"><input type="checkbox" name="ids" id="checkedAll"></th>
                                <th width="25%">@if(Session::has('lang') && Session::get('lang')=='bn') নাম @else Name @endif</th>
                                <th Width="10%">@if(Session::has('lang') && Session::get('lang')=='bn') লিঙ্ক @else Link @endif</th>
                                <th width="10%">@if(Session::has('lang') && Session::get('lang')=='bn') স্টেটাস @else Status @endif </th>
                                <th width="20%">@if(Session::has('lang') && Session::get('lang')=='bn') তারিখ @else Date @endif </th>
                                <th width="16%">@if(Session::has('lang') && Session::get('lang')=='bn') এডিট/ডিলিট @else Action @endif</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $page)
                            <tr style="border-bottom:1px solid rgba(0, 0, 0, 0.125)">
                                <td><input type="checkbox" name="selectedid" value="{{$page->id}}" id="id" class="checkSingle"></td>
                            </td>
                                <td>{{$page->name}}</td>
                                <td>{{$page->link}}</td>
                                <td style="margin:0 auto;text-align:center;">
                                    <div class="form-check form-switch" style="text-align:center;display:inline-flex">
                                        <input class="form-check-input switchstatus" type="checkbox" data-id="{{$page->id}}" id="flexSwitchCheckChecked" style="margin:0 auto;" @if($page->status=='active') checked @endif>
                                        <label class="form-check-label" for="flexSwitchCheckChecked"></label>
                                    </div>
                                </td>
                                <td>{{$page->created_at}}</td>
                                <td>
                                    <a href="{{URL::to('/')}}/page/edit/{{$page->id}}"><img src="{{asset('img/edit.png')}}" width="20px" height="20px"></a>
                                    &nbsp;&nbsp;
                                    <a href="{{URL::to('/')}}/page/delete/{{$page->id}}" onclick="return confirm('Are you sure you want to delete this item?');"><img src="{{asset('img/delete.png')}}" width="25px" height="25px"></a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="table-responsive" id="mobiletable">
                    <table class="table" width="100%">
                        @foreach($data as $key=>$page)
                        <tr class="mobilefirstrow">
                            <th width="10%">
                                <input type="checkbox" name="selectedid" value="{{$page->id}}" id="id" class="checkSingle">
                            </th>
                            <th width="20%" style="color:#f1593a">
                                Name
                            </th>
                            <td width="60%" style="color:black">
                                {{$page->name}}
                            </td>
                            <td width="10%">
                                <a href="#" class="toggler" data-prod-cat="{{$key}}">
                                <i class="fa fa-arrow-down" id="show{{$key}}" style="color:#f1593a"></i>
                                <i class="fa fa-arrow-up" id="up{{$key}}" style="display:none"></i>
                                </a>
                            </td>
                        </tr>
                        <tr class="cat{{$key}}" style="display:none">
                            <th width="10%"></th>
                            <th width="20%">
                                Link
                            </th>
                            <td width="60%">
                                 {{$page->link}}
                            </td>
                            <td width="10%"></td>
                        </tr>
                        <tr class="cat{{$key}}" style="display:none">
                            <th width="10%"></th>
                            <th width="20%">
                                Date
                            </th>
                            <td width="60%">
                                {{$page->created_at}}
                            </td>
                            <td width="10%"></td>
                        </tr>
                        <tr class="cat{{$key}}" style="display:none">
                            <th width="10%"></th>
                            <th width="20%">
                                Status
                            </th>
                            <td width="60%" style="display: flex;justify-content: center;align-items: center;">
                               <div class="form-check form-switch" style="text-align:center;display:inline-flex">
                                    <input class="form-check-input switchstatus" type="checkbox" data-id="{{$page->id}}" id="flexSwitchCheckChecked" style="margin:0 auto;" @if($page->status=='active') checked @endif>
                                    <label class="form-check-label" for="flexSwitchCheckChecked"></label>
                                </div>
                            </td>
                            <td width="10%"></td>
                        </tr>
                        <tr class="cat{{$key}}" style="display:none">
                            <th width="10%"></th>
                            <th width="20%">
                                Action
                            </th>
                            <td width="60%">
                                <a href="{{URL::to('/')}}/page/edit/{{$page->id}}"><img src="{{asset('img/edit.png')}}" width="20px" height="20px"></a>
                                    &nbsp;&nbsp;
                                <a href="{{URL::to('/')}}/page/delete/{{$page->id}}" onclick="return confirm('Are you sure you want to delete this item?');"><img src="{{asset('img/delete.png')}}" width="25px" height="25px"></a>
                            </td>
                            <td width="10%"></td>
                        </tr>
                        @endforeach
                    </table>
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
 $('#submit').on('click',function(){
     var form = $(this).parents('form');
     var note=$('#action').val();
     if(note != 'select'){
        swal.fire({
          title: 'Are you sure?',
          text: "You want to "+note+" this selected item",
          type: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Yes, '+note+' it!',
          cancelButtonText: 'No, cancel!',
          reverseButtons: true
        }).then((result) => {
          if (result.value) {
              console.log(form);
            $('#submitform').submit();
            form.submit();
          } else if (
            result.dismiss === Swal.DismissReason.cancel
          ){
            swal.fire(
              'Cancelled',
              ''+note+' Cancel :)',
              'error'
            )
          }
        })
     }
 })
    $(document).ready(function(){
        $('input[name=position]').change(function() {
            var value=$(this).val();
            var id=$(this).parent().parent().find("input[name=idss]").val();
            $url="/update-position-page";
            $.get($url,{value:value,id:id}, function(data){
                window.location.reload();
                // $('#testimonials').load(location.href + ' .testi');
            });
        });
    });
</script>
<script>
    $(document).ready(function(){
        $(".switchstatus").on("change",function(){
            $url="/changepagestatus";
            var value=$(this).val();
            console.log(value);
            var id = $(this).data('id');
            console.log(id);
            $.get($url,{value:value,id:id}, function(data){
               console.log(data);
            });
        });
    });
</script>
<script>
$(document).ready(function() {
    $("#checkedAll").change(function() {
        debugger;
        if (this.checked) {
            $(".checkSingle").each(function() {
                debugger;
                this.checked=true;
                var valuesArray = $('input[name="selectedid"]:checked').map(function () {
                return this.value;
                }).get().join(",");
                $("#selectids").val(valuesArray);
                $("#selectdelids").val(valuesArray);
            });
        } else {
            $(".checkSingle").each(function() {
                this.checked=false;
            });
            var valuesArray ='';
            $("#selectids").val(valuesArray);
            $("#selectdelids").val(valuesArray);
        }
    });
    $(".checkSingle").click(function () {
        if ($(this).is(":checked")) {
            var isAllChecked = 0;
            $(".checkSingle").each(function() {
                if (!this.checked)
                    isAllChecked = 1;
                var valuesArray = $('input[name="selectedid"]:checked').map(function () {
                return this.value;
                }).get().join(",");
                $("#selectids").val(valuesArray);
                $("#selectdelids").val(valuesArray);
            });
            if (isAllChecked == 0) {
                $("#checkedAll").prop("checked", true);
            }
        }
        else {
            $("#checkedAll").prop("checked", false);
            var valuesArray = $('input[name="selectedid"]:checked').map(function () {
                return this.value;
                }).get().join(",");
                $("#selectids").val(valuesArray);
                $("#selectdelids").val(valuesArray);
        }
    });
});
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
   function exportTasks(_this) {
      let _url = $(_this).data('href');
      window.location.href = _url;
   }
</script>
@endpush
