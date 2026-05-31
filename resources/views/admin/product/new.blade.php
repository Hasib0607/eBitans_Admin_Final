<?php
if (Auth::user()->type == 'admin' || Auth::user()->type == 'dropshipper') {
    $customerss = DB::table('customers')->where('uid', Auth::user()->id)->First();
    $store_id = $customerss->active_store;
} elseif (Auth::user()->type == 'staff') {
    $stafff = DB::table("staff")->where('uid', Auth::user()->id)->first();
    $store_id = $stafff->store_id;
}
$store = DB::table('stores')->where('id', $store_id)->first();
$store_name = $store->name;
$message = DB::table('messages')->where('uid', Auth::user()->id)->where('store_id', $store_id)->where('session', 'active')->get();
?>
<input type="hidden" name="messagecount" id="messagecount" value="{{count($message) ?? 0}}">
<input type="hidden" name="storename" id="storename" value="{{$store_name}}">
<input type="hidden" name="uid" id="uidss" value="{{Auth::user()->id}}">
<input type="hidden" name="chatidss" id="chatidss" value="0">
@if(isset($message) && count($message)>0)
    @foreach($message as $key=>$msg)
        @if($key==0)
            <input type="hidden" name="chatid" id="chatid" value="{{$msg->tokenid}}">
            <input type="hidden" name="uid" id="uid" value="{{Auth::user()->id}}">
        @endif
        @if(isset($msg->image))
            <li class="@if($msg->type=='send') send align-items-end @else receive @endif d-flex flex-column">
                    <span style="background-color:transparent;border:0px;max-width:fit-content">
                        <img src="https://admin.ebitans.com/assets/images/message/{{$msg->image}}" alt="" width="40px"/>
                    </span>

                <span style="background-color:#fff !important;border:0px;color:black;position:relative;font-size:9px;">
                        {{date('H:i:s', strtotime($msg->created_at))}}
                    </span>
            </li>
        @else
            <li class="@if($msg->type=='send') send align-items-end @else receive  @endif d-flex flex-column ">
                <span style="max-width:fit-content">{{$msg->message}}</span>

                <span style="background-color:#fff !important;border:0px;color:black;position:relative;font-size:9px;">
                        {{date('H:i:s', strtotime($msg->created_at))}}
                    </span>
            </li>
        @endif
    @endforeach
@endif
