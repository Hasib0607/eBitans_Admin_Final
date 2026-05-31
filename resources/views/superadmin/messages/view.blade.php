@extends('admin.layouts.main')
@section('content')
<main class="main-content position-relative h-100 border-radius-lg">
@include('superadmin.share.ss-bot-top-nav')
<div class="container-fluid mt-4" id="toplist">
    <div class="row">
        <div class="col-md-6">
            <h4>All Messages</h4>
        </div>
        <div class="col-md-6">
            <ul>
                <li style="padding:0px;border:0px;"><a href="" class="btn btn-primary" style="display:block;border-radius:0px !important">Create New</a></li>
                <!--<li><a href="">Import</a></li>-->
                <li style="padding:0px;border:0px;"><a href="#" style="display:block;border-radius:0px !important" class="btn btn-secondary">Export</a></li>
            </ul>
        </div>
    </div>
    <div class="row mt-5 productlist">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    All Message
                </div>
                <div class="card-body pt-1" style="height: 65vh; overflow-y: scroll;">
                    <table class="table">
                        @if(isset($member) && count($member)>0)
                        <input type="text" id="live-search-box" class="form-control mt-2 mb-2" placeholder="Client Search...">
                        @foreach($member as $key => $mmbs)

                        <tr style="background: #ff5733b0;" class="listdigital">

                            <td style="text-align:start; line-height: initial;">
                                <a href="{{URL::to('/')}}/messages/{{$mmbs->uid}}/{{$mmbs->store_id}}" style="display:block; color: white;font-size: 20px;"> {{$mmbs->name?? 'No Name'}}</a>
                                <span style="color: black; font-size: 12px;">{{ $mmbs->created_at }}</span>
                            </td>
                            <?php
                            $messages=DB::table('messages')->where('store_id',$mmbs->store_id)->where('seen',0)->get();
                            ?>
                            <td><a href="{{URL::to('/')}}/messages/{{$mmbs->uid}}/{{$mmbs->store_id}}" style="display:block"><span @if(count($messages)>0) style="background-color:red;color:#fff;padding:5px 10px;border-radius:25px;" @endif>{{count($messages) ?? "0"}}</span></a></td>

                        </tr>
                        @endforeach
                        @endif
                    </table>
                </div>
            </div>

        </div>
        <div class="chatbox1 col-md-8">
                <div class="card" >
            <div class="card-header">
                {{$name ?? ""}}
            </div>
            <div class="card-body" style="max-height:40vh;overflow-y:auto" id="message">
                <ul id="messgeul1" class="cartload1">

                    @if(isset($message) && count($message)>0)
                    @foreach($message as $key=>$msg)
                    @if($key==0)
                    <input type="hidden" name="chatid" id="chatid" value="{{$msg->tokenid}}">
                    <input type="hidden" name="uid" id="uid" value="{{$msg->uid}}">
                    @endif
                    @if(isset($msg->image))
                    <li class="@if($msg->type=='send') receive  @else send align-items-end @endif d-flex flex-column" >
                        <span style="background-color:transparent;border:0px;max-width:fit-content">
                            <img src="https://admin.ebitans.com/assets/images/message/{{$msg->image}}" alt="" width="40px"/>
                        </span>
                        <span style="background-color:#fff !important;border:0px;color:black;position:relative;font-size:9px;">
                            {{date('d-m-Y H:i:s', strtotime($msg->created_at))}}
                        </span>
                    </li>
                    @else
                    <li class="@if($msg->type=='send') receive  @else send align-items-end @endif d-flex flex-column">
                        <span style="max-width:fit-content">{{$msg->message}}</span>
                        <span style="background-color:#fff !important;border:0px;color:black;position:relative;font-size:9px;">
                            {{date('d-m-Y H:i:s', strtotime($msg->created_at))}}
                        </span>
                    </li>
                    @endif
                    @endforeach
                    @endif
                </ul>
            </div>
            <div style="display:none">
            <input id="inputFileToLoad" type="file" onchange="encodeImageFileAsURL();" style="visibility:hidden"/>
             <input type="hidden" name="base64img" id="base64img">
             <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
            </div>
            <div class="card-footer">
                <div class="send">
                    <input type="text" name="message" class="messagebox" style="position:relative;width:90%;height:55px">
                    <label for="inputFileToLoad" style="position:absolute;right:12%;margin-top:10px">
                    <span  id="inputimg"><img src="https://img.icons8.com/fluency/28/000000/image.png"/></span>
                    </label>
                    <a href="javascript:void(0)" class="btn btn-primary sendimgadmin"><i class='fa fa-send-o'></i></a>
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
    function update1(){
        $('#messgeul1').load(location.href + ' .cartload1');
        setTimeout(update1, 1000);
    }
    $(document).on('click', '.sendimgadmin', function(e) {
            var message=$('.messagebox').val();
            var chatid=$('#chatid').val();
            var uid=$('#uid').val();
            var img=$('#base64img').val();
            console.log(img);
            $.post("/sendmessageadmin", {chatid:chatid,uid:uid,message:message,img:img,"_token": "{{ csrf_token() }}"},function(data) {
                console.log(data['image']);
                $('.messagebox').val('');
                if(!(data['message'])){
                    $("#messgeul1").append(`<li class="send"><span style="background-color:transparent;border:0px;"><img src="https://admin.ebitans.com/assets/images/message/`+data['image']+`" alt="" width="40px"/></span></li>`);
                }else{
                $("#messgeul1").append(`<li class="send"><span>`+data['message']+`</span></li>`);
                }
                const element = document.getElementById('message');
                element.scrollTop = element.scrollHeight;
                window.setTimeout(update1, 1000);
              });
        });
    $(document).keypress(function(event){
          var keycode = (event.keyCode ? event.keyCode : event.which);
          if(keycode == '13'){
            var message=$('.messagebox').val();
            var chatid=$('#chatid').val();
            var uid=$('#uid').val();

            $.post("/sendmessageadmin", {chatid:chatid,uid:uid,message:message,"_token": "{{ csrf_token() }}"},function(data) {
                console.log(data);
                $('.messagebox').val('');
                if(typeof(data['message'])  === "undefined"){
                    $("#messgeul1").append(`<li class="send"><span style="background-color:transparent;border:0px;"><img src="https://admin.ebitans.com/assets/images/message/`+data['image']+`" alt="" width="40px"/></span></li>`);
                }else{
                $("#messgeul1").append(`<li class="send"><span>`+data['message']+`</span></li>`);
                }
                const element = document.getElementById('message');
                element.scrollTop = element.scrollHeight;
                window.setTimeout(update1, 1000);
              });
          }
        });
    $(document).ready(function(){
        $(".switchstatus").on("change",function(){
            $url="/changedesignstatus";
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


<script>
    jQuery(document).ready(function($){

        $('.listdigital td').each(function(){
        $(this).attr('data-search-term', $(this).text().toLowerCase());
        });

        $('#live-search-box').on('keyup', function(){

        var searchTerm = $(this).val().toLowerCase();

            $('.listdigital td').each(function(){

                if ($(this).filter('[data-search-term *= ' + searchTerm + ']').length > 0 || searchTerm.length < 1) {
                    $(this).show();
                } else {
                    $(this).hide();
                }

            });

        });

        });
</script>
@endpush
