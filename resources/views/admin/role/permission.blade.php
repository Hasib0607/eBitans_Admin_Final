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
            <h4>@if(Session::has('lang') && Session::get('lang')=='bn') অনুমতি যোগ করুন @else Add Permission @endif</h4>
        </div>
    </div>
    <div class="row mt-5 productlist">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h4>@if(Session::has('lang') && Session::get('lang')=='bn') অনুমতি  @else Permission @endif</h4>
                </div>
                <div class="card-body">
                    <form action="{{route('admin.savepermission',$role->id)}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3 row">
                        <table class="table" width="10%">
                            <?php

                            $permission=explode(',',$role->permission);
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
                    <div class="mb-3 row">
                        <label for="position" class="col-md-3 col-form-label"></label>
                        <div class="col-md-8" style="text-align:right">
                            <button type="submit" class="btn btn-info">@if(Session::has('lang') && Session::get('lang')=='bn') আপডেট @else Update @endif</button>
                        </div>
                    </div>
                    </form>
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