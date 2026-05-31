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
        <div class="col-md-8">
        <div class="card">
            <div class="card-header">

            </div>
            <div class="card-body">
                <p class="text-center">To see Conversation Select a Chat</p>
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
