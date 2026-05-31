@extends('admin.layouts.main')
@section('content')
<style>
    svg {
  background-color: #ffffff;
  border-radius: 50%;
  animation: spin 3s ease infinite alternate;
}

/*Give each dot a radius of 20*/
.shape {
  r: 20;
}

/*Give each dot its positioning and set the default animation and color for each */
.shape:nth-child(1) {
  cy: 50;
  cx: 50;
  fill: #c20f00;
  animation: movein 3s ease infinite alternate;
}
.shape:nth-child(2) {
  cy: 50;
  cx: 150;
  fill: #ffdd22;
  animation: movein 3s ease infinite alternate;
}
.shape:nth-child(3) {
  cy: 150;
  cx: 50;
  fill: #2374c6;
  animation: movein 3s ease infinite alternate;
}
.shape:nth-child(4) {
  cy: 150;
  cx: 150;
  fill: #000000;
  animation: movein 3s ease infinite alternate;
}
</style>
<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl " >
    <div class="modal-content loads" id="load">
        @if(Session::has('role'))
    <form action="{{route('admin.savepermission',Session::get('role'))}}" method="post" enctype="multipart/form-data">
                        @csrf
                        @endif
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Permission</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" >
         @if(Session::has('role'))
                        <div class="card-body">
                            <div class="mb-3 row">
                                <table class="table" width="10%">
                                    <?php
                                    $permission=Session::get('permission');
                                    foreach($permission as $key=>$pr){
                                        if($pr=='branch'){
                                            $branch=1;
                                        }elseif($pr=='product'){
                                            $product=1;
                                        }elseif($pr=='category'){
                                            $category=1;
                                        }elseif($pr=='subcategory'){
                                            $subcategory=1;
                                        }elseif($pr=='brand'){
                                            $brand=1;
                                        }elseif($pr=='attribute'){
                                            $attribute=1;
                                        }elseif($pr=='supplier'){
                                            $supplier=1;
                                        }
                                        elseif($pr=='collection'){
                                            $collection=1;
                                        }elseif($pr=='global_tab'){
                                            $global_tab=1;
                                        }elseif($pr=='coupon'){
                                            $coupon=1;
                                        }elseif($pr=='campaign'){
                                            $campaign=1;
                                        }elseif($pr=='offer'){
                                            $offer=1;
                                        }elseif($pr=='slider'){
                                            $slider=1;
                                        }elseif($pr=='banner'){
                                            $banner=1;
                                        }elseif($pr=='layouts'){
                                            $layouts=1;
                                        }elseif($pr=='template'){
                                            $template=1;
                                        }elseif($pr=='header'){
                                            $header=1;
                                        }elseif($pr=='homepage'){
                                            $homepage=1;
                                        }elseif($pr=='footer'){
                                            $footer=1;
                                        }elseif($pr=='mobilemenu'){
                                            $mobilemenu=1;
                                        }elseif($pr=='product_display'){
                                            $product_display=1;
                                        }elseif($pr=='product_grid'){
                                            $product_grid=1;
                                        }elseif($pr=='shop_page'){
                                            $shop_page=1;
                                        }elseif($pr=='pages'){
                                            $pages=1;
                                        }elseif($pr=='customer'){
                                            $customer=1;
                                        }elseif($pr=='staff'){
                                            $staff=1;
                                        }
                                        elseif($pr=='invoice'){
                                            $invoice=1;
                                        }elseif($pr=='setting'){
                                            $setting=1;
                                        }elseif($pr=='role_permission'){
                                            $role_permission=1;
                                        }elseif($pr=='pos'){
                                            $pos=1;
                                        }elseif($pr=='testimonials'){
                                            $testimonials=1;
                                        }elseif($pr=='theme_customize'){
                                            $theme_customize=1;
                                        }elseif($pr=='activity_log'){
                                            $activity_log=1;
                                        }
                                        elseif($pr=='inventory'){
                                            $inventory=1;
                                        }else{
                                            
                                        }
                                    }
                                    ?>
                                    <tr>
                                        <th width="1%"><input type="checkbox" name="permission[]" value="branch" @if(isset($branch) && $branch=='1') checked @endif> </th>
                                        <td width="16%">@if(Session::has('lang') && Session::get('lang')=='bn') শাখা @else Branch @endif</td>
                                        <th width="1%"><input type="checkbox" name="permission[]" value="product" @if(isset($product) && $product=='1') checked @endif></th>
                                        <td width="15%">@if(Session::has('lang') && Session::get('lang')=='bn') পণ্য @else Product @endif</td>
                                        <th width="1%"><input type="checkbox" name="permission[]" value="category" @if(isset($category) && $category=='1') checked @endif> </th>
                                        <td width="15%">@if(Session::has('lang') && Session::get('lang')=='bn') শ্রেণী @else Category @endif</td>
                                        <th width="1%"><input type="checkbox" name="permission[]" value="subcategory" @if(isset($subcategory) && $subcategory=='1') checked @endif></th>
                                        <td width="15%">@if(Session::has('lang') && Session::get('lang')=='bn') উপশ্রেণি @else Subcategory @endif</td>
                                        <th width="1%"><input type="checkbox" name="permission[]" value="brand" @if(isset($brand) && $brand=='1') checked @endif> </th>
                                        <td width="15%">@if(Session::has('lang') && Session::get('lang')=='bn') ব্র্যান্ড @else Brand @endif</td>
                                        <th width="1%"><input type="checkbox" name="permission[]" value="attribute" @if(isset($attribute) && $attribute=='1') checked @endif></th>
                                        <td width="20%">@if(Session::has('lang') && Session::get('lang')=='bn') বৈশিষ্ট্য @else Attribute @endif</td>
                                    </tr>
                                    <tr>
                                        <th width="1%"><input type="checkbox" name="permission[]" value="supplier" @if(isset($supplier) && $supplier=='1') checked @endif> </th>
                                        <td width="16%">@if(Session::has('lang') && Session::get('lang')=='bn') সরবরাহকারী @else Supplier @endif</td>
                                        <th width="1%"><input type="checkbox" name="permission[]" value="collection" @if(isset($collection) && $collection=='1') checked @endif></th>
                                        <td width="15%">@if(Session::has('lang') && Session::get('lang')=='bn') সংগ্রহ @else Collection @endif</td>
                                        <th width="1%"><input type="checkbox" name="permission[]" value="global_tab" @if(isset($global_tab) && $global_tab=='1') checked @endif> </th>
                                        <td width="15%">@if(Session::has('lang') && Session::get('lang')=='bn') গ্লোবাল ট্যাব @else Global Tab @endif</td>
                                        <th width="1%"><input type="checkbox" name="permission[]" value="coupon" @if(isset($coupon) && $coupon=='1') checked @endif></th>
                                        <td width="15%">@if(Session::has('lang') && Session::get('lang')=='bn') কুপন @else Coupon @endif</td>
                                        <th width="1%"><input type="checkbox" name="permission[]" value="campaign" @if(isset($campaign) && $campaign=='1') checked @endif> </th>
                                        <td width="15%">@if(Session::has('lang') && Session::get('lang')=='bn') প্রচারণা @else Campaign @endif</td>
                                        <th width="1%"><input type="checkbox" name="permission[]" value="offer" @if(isset($offer) && $offer=='1') checked @endif></th>
                                        <td width="20%">@if(Session::has('lang') && Session::get('lang')=='bn') অফার @else Offer @endif</td>
                                    </tr>
                                    <tr>
                                        <th width="1%"><input type="checkbox" name="permission[]" value="slider" @if(isset($slider) && $slider=='1') checked @endif> </th>
                                        <td width="16%">@if(Session::has('lang') && Session::get('lang')=='bn') স্লাইডার @else Slider @endif</td>
                                        <th width="1%"><input type="checkbox" name="permission[]" value="banner" @if(isset($banner) && $banner=='1') checked @endif></th>
                                        <td width="15%">@if(Session::has('lang') && Session::get('lang')=='bn') ব্যানার @else Banner @endif</td>
                                        <th width="1%"><input type="checkbox" name="permission[]" value="layouts" @if(isset($layouts) && $layouts=='1') checked @endif> </th>
                                        <td width="15%">@if(Session::has('lang') && Session::get('lang')=='bn') বিন্যাস @else Layouts @endif</td>
                                        <th width="1%"><input type="checkbox" name="permission[]" value="template" @if(isset($template) && $template=='1') checked @endif></th>
                                        <td width="15%">@if(Session::has('lang') && Session::get('lang')=='bn') টেমপ্লেট @else Template @endif</td>
                                        <th width="1%"><input type="checkbox" name="permission[]" value="header" @if(isset($header) && $header=='1') checked @endif> </th>
                                        <td width="15%">@if(Session::has('lang') && Session::get('lang')=='bn') হেডার @else Header @endif</td>
                                        <th width="1%"><input type="checkbox" name="permission[]" value="homepage" @if(isset($homepage) && $homepage=='1') checked @endif></th>
                                        <td width="20%">@if(Session::has('lang') && Session::get('lang')=='bn') হোমপেজ @else Homepage @endif</td>
                                    </tr>
                                    <tr>
                                        <th width="1%"><input type="checkbox" name="permission[]" value="footer" @if(isset($footer) && $footer=='1') checked @endif> </th>
                                        <td width="16%">@if(Session::has('lang') && Session::get('lang')=='bn') ফুটার @else Footer @endif</td>
                                        <th width="1%"><input type="checkbox" name="permission[]" value="mobilemenu" @if(isset($mobilemenu) && $mobilemenu=='1') checked @endif></th>
                                        <td width="15%">@if(Session::has('lang') && Session::get('lang')=='bn') মোবাইল মেনু @else Mobile Menu @endif</td>
                                        <th width="1%"><input type="checkbox" name="permission[]" value="product_display" @if(isset($product_display) && $product_display=='1') checked @endif> </th>
                                        <td width="15%">@if(Session::has('lang') && Session::get('lang')=='bn') পণ্য প্রদর্শন @else Product Display @endif</td>
                                        <th width="1%"><input type="checkbox" name="permission[]" value="product_grid" @if(isset($product_grid) && $product_grid=='1') checked @endif></th>
                                        <td width="15%">@if(Session::has('lang') && Session::get('lang')=='bn') পণ্য গ্রিড @else Product Grid @endif</td>
                                        <th width="1%"><input type="checkbox" name="permission[]" value="shop_page" @if(isset($shop_page) && $shop_page=='1') checked @endif> </th>
                                        <td width="15%">@if(Session::has('lang') && Session::get('lang')=='bn') দোকান পাতা @else Shop Page @endif</td>
                                        <th width="1%"><input type="checkbox" name="permission[]" value="pages" @if(isset($pages) && $pages=='1') checked @endif></th>
                                        <td width="20%">@if(Session::has('lang') && Session::get('lang')=='bn') পাতা @else Pages @endif</td>
                                    </tr>
                                    <tr>
                                        <th width="1%"><input type="checkbox" name="permission[]" value="customer" @if(isset($customer) && $customer=='1') checked @endif> </th>
                                        <td width="16%">@if(Session::has('lang') && Session::get('lang')=='bn') ক্রেতা @else Customer @endif</td>
                                        <th width="1%"><input type="checkbox" name="permission[]" value="staff" @if(isset($staff) && $staff=='1') checked @endif></th>
                                        <td width="15%">@if(Session::has('lang') && Session::get('lang')=='bn') কর্মী @else Staff @endif</td>
                                        <th width="1%"><input type="checkbox" name="permission[]" value="invoice" @if(isset($invoice) && $invoice=='1') checked @endif> </th>
                                        <td width="15%">@if(Session::has('lang') && Session::get('lang')=='bn') চালান @else Invoice @endif</td>
                                        <th width="1%"><input type="checkbox" name="permission[]" value="setting" @if(isset($setting) && $setting=='1') checked @endif></th>
                                        <td width="15%">@if(Session::has('lang') && Session::get('lang')=='bn') স্থাপন @else Setting @endif</td>
                                        <th width="1%"><input type="checkbox" name="permission[]" value="role_permission" @if(isset($role_permission) && $role_permission=='1') checked @endif> </th>
                                        <td width="15%">@if(Session::has('lang') && Session::get('lang')=='bn') ভূমিকা এবং অনুমতি @else Role and Permission @endif</td>
                                        <th width="1%"><input type="checkbox" name="permission[]" value="pos" @if(isset($pos) && $pos=='1') checked @endif></th>
                                        <td width="20%">@if(Session::has('lang') && Session::get('lang')=='bn') পাস্ @else Pos @endif</td>
                                    </tr>
                                    <tr>
                                        <th width="1%"><input type="checkbox" name="permission[]" value="testimonials" @if(isset($testimonials) && $testimonials=='1') checked @endif> </th>
                                        <td width="16%">@if(Session::has('lang') && Session::get('lang')=='bn') প্রশংসাপত্র @else Testimonials @endif</td>
                                        <th width="1%"><input type="checkbox" name="permission[]" value="theme_customize" @if(isset($theme_customize) && $theme_customize=='1') checked @endif> </th>
                                        <td width="16%">@if(Session::has('lang') && Session::get('lang')=='bn') থিম কাস্টমাইজ করুন @else Theme Customize @endif</td>
                                        <th width="1%"><input type="checkbox" name="permission[]" value="activity_log" @if(isset($activity_log) && $activity_log=='1') checked @endif> </th>
                                        <td width="16%">@if(Session::has('lang') && Session::get('lang')=='bn') কার্য বিবরণ @else Activity Log @endif</td>
                                        <th width="1%"><input type="checkbox" name="permission[]" value="inventory" @if(isset($inventory) && $inventory=='1') checked @endif> </th>
                                        <td width="16%">@if(Session::has('lang') && Session::get('lang')=='bn') ইনভেন্টরি @else Inventory @endif</td>
                                    </tr>
                                </table>
                            </div>
                            
                        </div>
        @else
        
        @endif
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary cholse" onclick="hidedivs()" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-info">@if(Session::has('lang') && Session::get('lang')=='bn') আপডেট @else Update @endif</button>
        
      </div>
    </form>
    </div>
  </div>
</div>
<div class="modal fade" id="exampleModal1" tabindex="-1" aria-labelledby="exampleModalLabel1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl " >
    <div class="modal-content">
    <form action="{{route('admin.saverole')}}" method="post" enctype="multipart/form-data">
                        @csrf
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel1">Add Permission</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" >
         @if(Session::has('role'))
                        <div class="card-body">
                            <input type="hidden" name="namess" id="namess">
                            <div class="mb-3 row">
                                <table class="table" width="10%">
                                    <tr>
                                        <th width="1%"><input type="checkbox" name="permission[]" value="branch"> </th>
                                        <td width="16%">@if(Session::has('lang') && Session::get('lang')=='bn') শাখা @else Branch @endif</td>
                                        <th width="1%"><input type="checkbox" name="permission[]" value="product"></th>
                                        <td width="15%">@if(Session::has('lang') && Session::get('lang')=='bn') পণ্য @else Product @endif</td>
                                        <th width="1%"><input type="checkbox" name="permission[]" value="category"> </th>
                                        <td width="15%">@if(Session::has('lang') && Session::get('lang')=='bn') শ্রেণী @else Category @endif</td>
                                        <th width="1%"><input type="checkbox" name="permission[]" value="subcategory"></th>
                                        <td width="15%">@if(Session::has('lang') && Session::get('lang')=='bn') উপশ্রেণি @else Subcategory @endif</td>
                                        <th width="1%"><input type="checkbox" name="permission[]" value="brand"> </th>
                                        <td width="15%">@if(Session::has('lang') && Session::get('lang')=='bn') ব্র্যান্ড @else Brand @endif</td>
                                        <th width="1%"><input type="checkbox" name="permission[]" value="attribute"></th>
                                        <td width="20%">@if(Session::has('lang') && Session::get('lang')=='bn') বৈশিষ্ট্য @else Attribute @endif</td>
                                    </tr>
                                    <tr>
                                        <th width="1%"><input type="checkbox" name="permission[]" value="supplier"> </th>
                                        <td width="16%">@if(Session::has('lang') && Session::get('lang')=='bn') সরবরাহকারী @else Supplier @endif</td>
                                        <th width="1%"><input type="checkbox" name="permission[]" value="collection"></th>
                                        <td width="15%">@if(Session::has('lang') && Session::get('lang')=='bn') সংগ্রহ @else Collection @endif</td>
                                        <th width="1%"><input type="checkbox" name="permission[]" value="global_tab"> </th>
                                        <td width="15%">@if(Session::has('lang') && Session::get('lang')=='bn') গ্লোবাল ট্যাব @else Global Tab @endif</td>
                                        <th width="1%"><input type="checkbox" name="permission[]" value="coupon"></th>
                                        <td width="15%">@if(Session::has('lang') && Session::get('lang')=='bn') কুপন @else Coupon @endif</td>
                                        <th width="1%"><input type="checkbox" name="permission[]" value="campaign"> </th>
                                        <td width="15%">@if(Session::has('lang') && Session::get('lang')=='bn') প্রচারণা @else Campaign @endif</td>
                                        <th width="1%"><input type="checkbox" name="permission[]" value="offer"></th>
                                        <td width="20%">@if(Session::has('lang') && Session::get('lang')=='bn') অফার @else Offer @endif</td>
                                    </tr>
                                    <tr>
                                        <th width="1%"><input type="checkbox" name="permission[]" value="slider"> </th>
                                        <td width="16%">@if(Session::has('lang') && Session::get('lang')=='bn') স্লাইডার @else Slider @endif</td>
                                        <th width="1%"><input type="checkbox" name="permission[]" value="banner"></th>
                                        <td width="15%">@if(Session::has('lang') && Session::get('lang')=='bn') ব্যানার @else Banner @endif</td>
                                        <th width="1%"><input type="checkbox" name="permission[]" value="layouts"> </th>
                                        <td width="15%">@if(Session::has('lang') && Session::get('lang')=='bn') বিন্যাস @else Layouts @endif</td>
                                        <th width="1%"><input type="checkbox" name="permission[]" value="template"></th>
                                        <td width="15%">@if(Session::has('lang') && Session::get('lang')=='bn') টেমপ্লেট @else Template @endif</td>
                                        <th width="1%"><input type="checkbox" name="permission[]" value="header"> </th>
                                        <td width="15%">@if(Session::has('lang') && Session::get('lang')=='bn') হেডার @else Header @endif</td>
                                        <th width="1%"><input type="checkbox" name="permission[]" value="homepage"></th>
                                        <td width="20%">@if(Session::has('lang') && Session::get('lang')=='bn') হোমপেজ @else Homepage @endif</td>
                                    </tr>
                                    <tr>
                                        <th width="1%"><input type="checkbox" name="permission[]" value="footer"> </th>
                                        <td width="16%">@if(Session::has('lang') && Session::get('lang')=='bn') ফুটার @else Footer @endif</td>
                                        <th width="1%"><input type="checkbox" name="permission[]" value="mobilemenu"></th>
                                        <td width="15%">@if(Session::has('lang') && Session::get('lang')=='bn') মোবাইল মেনু @else Mobile Menu @endif</td>
                                        <th width="1%"><input type="checkbox" name="permission[]" value="product_display"> </th>
                                        <td width="15%">@if(Session::has('lang') && Session::get('lang')=='bn') পণ্য প্রদর্শন @else Product Display @endif</td>
                                        <th width="1%"><input type="checkbox" name="permission[]" value="product_grid"></th>
                                        <td width="15%">@if(Session::has('lang') && Session::get('lang')=='bn') পণ্য গ্রিড @else Product Grid @endif</td>
                                        <th width="1%"><input type="checkbox" name="permission[]" value="shop_page"> </th>
                                        <td width="15%">@if(Session::has('lang') && Session::get('lang')=='bn') দোকান পাতা @else Shop Page @endif</td>
                                        <th width="1%"><input type="checkbox" name="permission[]" value="pages"></th>
                                        <td width="20%">@if(Session::has('lang') && Session::get('lang')=='bn') পাতা @else Pages @endif</td>
                                    </tr>
                                    <tr>
                                        <th width="1%"><input type="checkbox" name="permission[]" value="customer"> </th>
                                        <td width="16%">@if(Session::has('lang') && Session::get('lang')=='bn') ক্রেতা @else Customer @endif</td>
                                        <th width="1%"><input type="checkbox" name="permission[]" value="staff"></th>
                                        <td width="15%">@if(Session::has('lang') && Session::get('lang')=='bn') কর্মী @else Staff @endif</td>
                                        <th width="1%"><input type="checkbox" name="permission[]" value="invoice"> </th>
                                        <td width="15%">@if(Session::has('lang') && Session::get('lang')=='bn') চালান @else Invoice @endif</td>
                                        <th width="1%"><input type="checkbox" name="permission[]" value="setting"></th>
                                        <td width="15%">@if(Session::has('lang') && Session::get('lang')=='bn') স্থাপন @else Setting @endif</td>
                                        <th width="1%"><input type="checkbox" name="permission[]" value="role_permission"> </th>
                                        <td width="15%">@if(Session::has('lang') && Session::get('lang')=='bn') ভূমিকা এবং অনুমতি @else Role and Permission @endif</td>
                                        <th width="1%"><input type="checkbox" name="permission[]" value="pos"></th>
                                        <td width="20%">@if(Session::has('lang') && Session::get('lang')=='bn') পাস্ @else Pos @endif</td>
                                    </tr>
                                    <tr>
                                        <th width="1%"><input type="checkbox" name="permission[]" value="testimonials"> </th>
                                        <td width="16%">@if(Session::has('lang') && Session::get('lang')=='bn') প্রশংসাপত্র @else Testimonials @endif</td>
                                        <th width="1%"><input type="checkbox" name="permission[]" value="theme_customize"> </th>
                                        <td width="16%">@if(Session::has('lang') && Session::get('lang')=='bn') থিম কাস্টমাইজ করুন @else Theme Customize @endif</td>
                                        <th width="1%"><input type="checkbox" name="permission[]" value="activity_log"> </th>
                                        <td width="16%">@if(Session::has('lang') && Session::get('lang')=='bn') কার্য বিবরণ @else Activity Log @endif</td>
                                        <th width="1%"><input type="checkbox" name="permission[]" value="inventory"> </th>
                                        <td width="16%">@if(Session::has('lang') && Session::get('lang')=='bn') ইনভেন্টরি @else Inventory @endif</td>
                                    </tr>
                                </table>
                            </div>
                            
                        </div>
        @else
        
        @endif
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary cholse" onclick="hidedivs1()" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-info">@if(Session::has('lang') && Session::get('lang')=='bn') আপডেট @else Update @endif</button>
        
      </div>
    </form>
    </div>
  </div>
</div>
<div class="modal fade" id="exampleModal2" tabindex="-1" aria-labelledby="exampleModalLabel2" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" >
    <div class="modal-content">
    <form action="{{route('admin.editrole')}}" method="post" enctype="multipart/form-data">
                        @csrf
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel2">Edit Role</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" >
         <div class="row">
             <label for="" class="col-3">
                 Name
             </label>
             <div class="col-9">
                 <input type="hidden" name="id" id="roleid" class="form-control">
                 <input type="text" name="name" id="pname" class="form-control">
             </div>
         </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary cholse" onclick="hidedivs2()" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-info">@if(Session::has('lang') && Session::get('lang')=='bn') আপডেট @else Update @endif</button>
        
      </div>
    </form>
    </div>
  </div>
</div>

<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
<div class="container-fluid navbars" style="background-image: linear-gradient(53deg, #42424a 0%, #191919 100%);">
    <div class="row">
        <div class="col-md-12">
            <nav style="--bs-breadcrumb-divider: '';" aria-label="breadcrumb">
                <ol class="breadcrumb" style="background-color:transparent;color:#fff">
                    <li class="breadcrumb-item">
                        <a href="{{route('admin.staff')}}">
                            <img src="{{URL::to('/')}}/img/icons/employee.png"> <br> <span class="nav-link-text ms-1">@if(Session::has('lang') && Session::get('lang')=='bn') স্টাফ @else Employee @endif</span>
                        </a>
                    </li>
                    <li class="breadcrumb-item active">
                        <a href="{{route('admin.role.permission')}}">
                            <img src="{{URL::to('/')}}/img/icons/permissions.png"> <br> <span class="nav-link-text ms-1">@if(Session::has('lang') && Session::get('lang')=='bn') ভূমিকা এবং অনুমতি @else Role & Permission @endif</span>
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
            <h4>@if(Session::has('lang') && Session::get('lang')=='bn') সব ভূমিকা  @else All Roles @endif</h4>
        </div>
    </div>
    <div class="row mt-5 productlist">
        <div class="col-lg-4 col-md-12 col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h4>@if(Session::has('lang') && Session::get('lang')=='bn') নতুন ভূমিকা যোগ করুন  @else Add Roles @endif</h4>
                </div>
                <div class="card-body">
                    <!--<form action="{{route('admin.saverole')}}" method="post" enctype="multipart/form-data">-->
                    @csrf
                    <div class="mb-3 row">
                        <label for="staticEmail" class="col-md-3 col-form-label">@if(Session::has('lang') && Session::get('lang')=='bn') নাম @else Name @endif</label>
                        <div class="col-md-8">
                        <input type="text" class="form-control" id="staticEmail" name="name" placeholder="Role Name">
                            @error('name')
                                <p class="text-danger">{{$message}}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="position" class="col-md-3 col-form-label"></label>
                        <div class="col-md-8" style="text-align:right">
                            <button type="button" onclick="submitss()"  class="btn btn-info">@if(Session::has('lang') && Session::get('lang')=='bn') সাবমিট  @else Submit @endif</button>
                        </div>
                    </div>
                    <!--</form>-->
                </div>
            </div>
        </div>
        <div class="col-lg-8 col-md-12 col-sm-12 mt-1 mb-1">
        <div class="card">
            <div class="card-header">
                @if (Session::has('success_message'))
                    <div class="alert alert-success">{{Session::get('success_message')}}</div>
                @endif
                <div class="row">
                    <div class="col-md-2" style="padding-right:1px;">
                        <form id="submitform" method="post" action="{{route('admin.changerolessstatus')}}">
                        @csrf
                    <input type="hidden" name="text2" id="selectids">
                        <select class='form-control' name="action" id="action">
                            <option value="select">@if(Session::has('lang') && Session::get('lang')=='bn') সিলেক্ট  অপসন @else Select Option @endif</option>
                            <option value="delete">@if(Session::has('lang') && Session::get('lang')=='bn') ডিলিট @else Delete @endif</option>
                        </select>
                    </div>
                    <div class="col-md-1" style="padding-left:0px;">
                        <p id="submit" class="btn btn-primary filterbuttonss">@if(Session::has('lang') && Session::get('lang')=='bn') আবেদন  @else Apply @endif</p>
                        </form>
                    </div>
                    <div class="col-md-6"></div>
                    <div class="col-md-3">
                        <div class="input-group" >
                            <input type="text" class="form-control" aria-label="Dollar amount (with dot and two decimal places)" id="taskfilter">
                            <span class="input-group-text" style="padding: 0.75rem 11px !important;"><i class="fa fa-search"></i></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped" width="100%" id="taskfilterresult">
                        <thead>
                            <tr>
                                <th width="4%"><input type="checkbox"></th>
                                <th width="30%">@if(Session::has('lang') && Session::get('lang')=='bn') নাম @else Name @endif</th>                              
                                <th width="21%">@if(Session::has('lang') && Session::get('lang')=='bn') এডিট/ডিলিট @else Action @endif</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($roles as $role)
                            <tr style="border-bottom:1px solid rgba(0, 0, 0, 0.125)">
                                <td><input type="checkbox" name="id" value="{{$role->id}}"></td>
                                <td>{{$role->name}}</td>
                                <td>
                                    <a href="{{route('admin.deleterole',$role->id)}}" style="float:right;margin-right:5px"><img src="{{asset('img/delete.png')}}" width="25px" height="25px"></a>                                   
                                    <a  style="float:right;margin-right:5px;" onclick="permissionedit({{$role->id}})" data-bs-whatever="{{$role->id}}"><img src="{{asset('img/edit.png')}}" width="20px" height="20px"></a>                              
                                    <a  style="float:right;margin-right:5px;" class="btn btn-secondary permissionss loging" onclick="permission({{$role->id}})" data-bs-toggle="modal" data-id="{{$role->id}}"  data-bs-whatever="{{$role->id}}">@if(Session::has('lang') && Session::get('lang')=='bn') অনুমতি @else Permission @endif</a>
                                    <a style="float:right;margin-right:5px;" class="buttonload btn bg-gradient-primary" id="buttonload">
                                      <i class="fa fa-spinner fa-spin"></i>&nbsp;Loading
                                    </a>
                                <!--href="{{URL::to('/')}}/role-and-permission/{{encrypt($role->id)}}/permission" data-bs-target="#exampleModal"  href="{{URL::to('/')}}/role-and-permission/{{encrypt($role->id)}}/edit"-->
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
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
  $(".buttonload").hide();
  $(".loging").on('click',function(){
     $(this).hide();
     title = $(this).closest('tr').find('.buttonload');
     title.show();
     setTimeout(() => {
         $(this).show();
         title.hide();
     }, 500);
  });
function permission(id){
    var id=id;
    
    $.get('/pro',{id:id},function(data){
           $('#load').load(location.href + ' .loads');
           $('#exampleModal').show();
           $('.beforeload').show();
           $('.afterload').hide();
           setTimeout(() => {
               $('.beforeload').hide();
               $('.afterload').show();
                
           }, 4000);
           
      })
    console.log(id);
}
function permissionedit(id){
    var id=id;
    console.log(id);
    $.get('/role/name',{id:id},function(data){
        console.log("data",data);
        $('#roleid').val(data.id);
        $('#pname').val(data.name);
        $('#exampleModal2').show();
    })
}
function hidedivs(){
    debugger;
    $('#exampleModal').hide();
}
function hidedivs2(){
    debugger;
    $('#exampleModal2').hide();
}
function hidedivs1(){
    debugger;
    $('#exampleModal1').hide();
}
$('.cholse').on('click',function(){
    debugger;
    
}) 
function submitss(){
    var name=$('input[name=name]').val();
    console.log(name);
    $('input[name=namess]').val(name);
    if(name == ''){
        $('#exampleModal1').hide();
    }else{
        $('#exampleModal1').show();
    }
}
// $(".permissionss").on('click',function(){
//     $('#exampleModal').show();
//     var id=$(this).data('id');
//     console.log(id)
//     var exampleModal = document.getElementById('exampleModal')
//     exampleModal.addEventListener('show.bs.modal', function (event) {
//       // Button that triggered the modal
//       var button = event.relatedTarget
//       // Extract info from data-bs-* attributes
//       var id = $('.permissionss').getAttribute('data-bs-whatever')
//       console.log(id);
//       $.get('/pro',{id:id},function(data){
//           $('#load').load(location.href + ' .loads');
//           exampleModal.show();
          
//       })
//     })
// })
   
</script>
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