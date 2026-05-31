<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Plan;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Validator;
use Session;
use App\Models\Veriant;
use App\Models\Customer;
use App\Models\Staff;
use Auth;


class CheckroleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
     public function __construct()
    {
        $this->middleware('auth');
    }
     public function index(){
         if(Auth::user()->type=='staff'){
                $staff=DB::table('staff')->where('uid',Auth::user()->id)->first();
                $store_id=$staff->store_id;
                $role=DB::table("roles")->where('id',$staff->role_id)->first();
                if(isset($role)){
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
                            }else{
                                
                            }
                        }
                }
            }
     }
}