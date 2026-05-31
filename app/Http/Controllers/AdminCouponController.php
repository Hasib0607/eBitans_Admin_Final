<?php

namespace App\Http\Controllers;

use App\Http\Traits\ActivityLogTraits;
use App\Models\AdminCoupon;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class AdminCouponController extends Controller
{
    use ActivityLogTraits;

     public function __construct()
    {
        $this->middleware('auth');
    }


    public function changecouponstatus(Request $request){
        $user=Auth::user()->id;
        $user_type=Auth::user()->type;


        $id=$request->id;
        $value=$request->value;
        $coupon = AdminCoupon::where('id', $id)->first();
        if(empty($coupon)){
            return back();
        }
        if(isset($coupon) && $coupon->status == 'active'){
            $coupon->status='inactive';
        }else{
            $coupon->status="active";
        }
        $coupon->save();
        $data=$coupon;
        $activity=" Change Coupon Status ".$coupon->code;
        $this->saveactivity($activity);
        return response()->json($data);
    }


    public function couponsave(Request $request){
        $rules = array(
            'code'  =>'required|unique:admin_coupons',
            'start_date'=>'required',
            'end_date'=>'required',
            'min_purchase'=>'required',
            'max_use'=>'required',
            'discount_type'=>'required',
            'discount_amount'=>'required'
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator);
        }else{
                $coupon=new AdminCoupon;
                $coupon->code=$request->code;
                $coupon->start_date=$request->start_date;
                $coupon->end_date=$request->end_date;
                $coupon->min_purchase=$request->min_purchase;
                $coupon->max_purchase=$request->max_purchase;
                $coupon->max_use=$request->max_use;
                $coupon->discount_type=$request->discount_type;
                $coupon->discount_amount=$request->discount_amount;

                if($request->status=="on"){
                    $coupon->status="active";
                }else{
                    $coupon->status="inactive";
                }

                $coupon->save();
                Session::flash('success_message','Coupon Save Successfully !');
                return redirect()->route('superadmin.promotion.coupon');
            }
    }
    public function couponexport(Request $request)
    {
        $date=Carbon::now();
        $user=Auth::user()->id;
        $user_type=Auth::user()->type;

        $fileName = 'coupon('.$date.').csv';
        $coupon = AdminCoupon::get();

        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $columns = array('Name', 'Code','Start Date','End Date','Discount Type','Discount Amount','Minimum Purchase','Maximum Purchase','Maximum Use','Created_at');

        $callback = function() use($coupon, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($coupon as $cat) {
                $row['Code']    = $cat->code;
                $row['Start Date'] =$cat->start_date;
                $row['End Date'] = $cat->end_date;
                $row['Discount Type'] =$cat->discount_type;
                $row['Discount Amount'] =$cat->discount_amount;
                $row['Minimum Purchase']=$cat->min_purchase;
                $row['Maximum Purchase']=$cat->max_purchase;
                $row['Maximum Use']=$cat->max_use;
                $row['Create Date']  = $cat->created_at;

                fputcsv($file, array($row['Name'], $row['Code'],$row['Start Date'],$row['End Date'],$row['Discount Type'],$row['Discount Amount'],$row['Minimum Purchase'],$row['Maximum Purchase'],$row['Maximum Use'],$row['Create Date']));
            }

            fclose($file);
        };

        $activity=" Export Coupon";
        $this->saveactivity($activity);
        return response()->stream($callback, 200, $headers);
    }

    public function coupon()
    {
        $urls="promotion";

        $coupons=AdminCoupon::get();
        return view('superadmin.promotion.allcoupon')->with('coupons',$coupons)->with('urls',$urls);
    }


    public function editcoupon($id){


        $coupon=AdminCoupon::where('id', $id)->first();

        if(empty($coupon)){
            return back();
        }

        $activity=" Edit Coupon ".$coupon->code;
        $this->saveactivity($activity);
       $urls='urls';
        return view('superadmin.promotion.editcoupon')->with('coupon',$coupon)->with('urls',$urls);

    }

    public function updatecoupon(Request $request,$id){
        $rules = array(
            'code'  =>'required',
            'start_date'=>'required',
            'end_date'=>'required',
            'min_purchase'=>'required',
            'max_use'=>'required',
            'discount_type'=>'required',
            'discount_amount'=>'required'
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator);
        }else{

                $coupon=AdminCoupon::where('id', $id)->first();
                if(empty($coupon)){
                    return back();
                }

                $coupon->code=$request->code;
                $coupon->start_date=$request->start_date;
                $coupon->end_date=$request->end_date;
                $coupon->min_purchase=$request->min_purchase;
                $coupon->max_purchase=$request->max_purchase;
                $coupon->max_use=$request->max_use;
                $coupon->discount_type=$request->discount_type;
                $coupon->discount_amount=$request->discount_amount;
                if($request->status=="on"){
                    $coupon->status="active";
                }else{
                    $coupon->status="inactive";
                }


                $coupon->save();

                Session::flash('success_message','Coupon Update Successfully !');
                return redirect()->route('superadmin.promotion.coupon');
            }
    }
    public function deletecoupon($id){
            $coupon=AdminCoupon::where('id', $id)->first();
            if(empty($coupon)){
                return back();
            }
            $coupon->delete();
            Session::flash('success_message','Coupon Delete Successfully !');
            return redirect()->route('superadmin.promotion.coupon');
    }


    public function changecouponsstatus(Request $request){
        if($request->text2==''){
            Session::flash('message','Please Select at least one item');
            return back();
        }
        if($request->action=='select'){
            Session::flash('message','Please Select a Option');
            return back();
        }

        if($request->action=='active'){
            $id=explode(',',$request->text2);
            if(isset($id) && count($id)>0){
                foreach($id as $ids){
                    $product=AdminCoupon::find($ids);
                    $product->status='active';
                    $product->save();
                }
            }
            $activity=" Change Coupon Status";
            $this->saveactivity($activity);
            Session::flash('message','Successfully Active Coupon');
            return back();
        }
        if($request->action=='deactive'){
            $id=explode(',',$request->text2);
            if(isset($id) && count($id)>0){
                foreach($id as $ids){
                    $product=AdminCoupon::find($ids);
                    $product->status='deactive';
                    $product->save();
                }
            }
            $activity=" Change Coupon Status";
            $this->saveactivity($activity);
            Session::flash('message','Successfully Deactive Coupon');
            return back();
        }
        if($request->action=='delete'){
            $id=explode(',',$request->text2);
            if(isset($id) && count($id)>0){
                foreach($id as $ids){
                    $product=AdminCoupon::find($ids);
                    $product->delete();
                }
            }
            $activity=" Delete Coupon";
            $this->saveactivity($activity);
            Session::flash('message','Successfully Deleted Coupon');
            return back();
        }
    }

}
