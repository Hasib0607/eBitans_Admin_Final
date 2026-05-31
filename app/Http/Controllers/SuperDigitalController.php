<?php

namespace App\Http\Controllers;

use App\Http\Middleware\Digitalplan;
use Illuminate\Http\Request;
use App\Models\Orderitem;
use App\Models\Order;
use App\Models\Content;
use App\Models\Customer;
use App\Models\Staff;
use App\Models\Store;
use App\Models\Campaign;
use App\Models\Product;
use App\Models\Category;
use App\Models\Branchproduct;
use App\Models\Boosting;
use App\Models\Digitalcontent;
use App\Models\Planorder;
use App\Models\RequiredInformation;
use App\Models\RequiredInformationForContent;
// use Session;
// use Auth;
use App\Models\Toptool;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;

class SuperDigitalController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function digitalmarketing()
    {
        $digitalPlan = Planorder::where('customer_id', Auth::user()->id)->get();
        // dd(Auth::user()->id);
        $urls="digitalmarketing";
        return view('superadmin.digital.index', compact('urls'));
    }
    public function boosting()
    {
        $urls="digitalmarketing";
        $boostings=Boosting::all();
        return view('superadmin.digital.boosting', compact('urls', 'boostings'));
    }
    public function changeboostingstatus($id, $status)
    {
        $boostings=Boosting::find($id);
        $boostings->status=$status;
        $boostings->save();
        Session::flash('message', 'Successfully Updated');
        return back();
    }
    public function deleteboosting($id)
    {
        $boostings=Boosting::find($id);
        $boostings->delete();
        Session::flash('message', 'Successfully Deleted');
        return back();
    }
    public function submitboosting(Request $request)
    {
        $user=Auth::user()->id;
        $user_type=Auth::user()->type;
        if ($user_type=='admin') {
            $customer=Customer::where('uid', $user)->first();
            $store_id=$customer->active_store;
            $customer_id=$customer->id;
        } elseif ($user_type=='staff') {
            $staff=Staff::where('uid', $user)->first();
            $store_id=$staff->store_id;
            $customer_id=$staff->customer_id;
        }
        $boost=new Boosting();
        $boost->type=$request->type;
        $boost->amount=$request->amount;
        $boost->from=$request->from;
        $boost->to=$request->to;
        $boost->status="Pending";
        $boost->note=$request->note;
        $boost->content=$request->content ?? null;
        $boost->uid=$user;
        $boost->store_id=$store_id;
        $boost->customer_id=$customer_id;
        $boost->save();
        Session::flash('message', 'Successfully save Boosting Request');
        return back();
    }

    public function content()
    {
        $urls="digital";
        $id = '';
        $lists=Store::where('digital_plan_id', '!=', null)->get();
        return view('superadmin.digital.content', compact('urls', 'lists', 'id'));
    }

    public function requiredContent()
    {
        $urls="digital";
        $id = '';
        $lists=Store::where('digital_plan_id', '!=', null)->get();
        return view('superadmin.digital.required_content', compact('urls', 'lists', 'id'));
    }

    public function requiredContentDownload(Request $request)
    {
        $filepath = public_path($request->pathName);

        return Response::download($filepath);
    }

    public function requiredContentView($id)
    {
        $data['id'] = "";
        $data['urls'] ="digital";
        $data['lists'] = Store::where('digital_plan_id', '!=', null)->get();

        $data['required_information'] = RequiredInformation::where('store_id', $id)->first();
        $data['required_information_contents'] = RequiredInformationForContent::where('store_id', $id)->orderBy('id', 'DESC')->get();


        return view('superadmin.digital.required_content', $data);
    }

    public function requiredContentDelete($id)
    {

        $data = RequiredInformationForContent::find($id);
        $data -> delete();

        return back()->with('success', 'আপনার তথ্যটি মুছে ফেলা হয়েছে।');
    }


    public function contentview($id)
    {
        $urls="digital";
        $lists=Store::where('digital_plan_id', '!=', null)->get();
        $content=Content::where('store_id', $id)->get();
        //////
        $list=Store::find($id);
        // dd($list);
        $startDate = $list->digital_plan_start_date." 00:00:00";
        $endDate = $list->digital_plan_expiry_date." 00:00:00";

        $sc=Content::where('store_id', $id)->where('type', 'Static Content')->where('created_at', '>=', $startDate)->where('created_at', '>=', $endDate)->count();
        $vc=Content::where('store_id', $id)->where('type', 'Video Content')->where('created_at', '>=', $startDate)->where('created_at', '>=', $endDate)->count();
        $gc=Content::where('store_id', $id)->where('type', 'Gify Content')->where('created_at', '>=', $startDate)->where('created_at', '>=', $endDate)->count();
        $cw=Content::where('store_id', $id)->where('type', 'Caption Writting')->where('created_at', '>=', $startDate)->where('created_at', '>=', $endDate)->count();

        //////
        $id=$id;
        return view('superadmin.digital.content', compact('urls', 'lists', 'content', 'id', 'sc', 'vc', 'gc', 'cw'));
    }

    public function savecontent(Request $request)
    {
        // dd($request->all());
        // dd(base_path().'\clientContent');



        if($request->type == 'Caption Writting'){
            $content_name = $request->content;
        }else{
            $content_name = time().'.'.$request->content->extension();
            $request->content->move(public_path('clientContent'), $content_name);
        }

        if ($request->store=='0') {
            Session::flash('message', 'Please Select Store');
            return back();
        }
        if ($request->type=='0') {
            Session::flash('message', 'Please Select Type');
            return back();
        }

        $content=new Content();
        $content->name=$request->name;
        $content->store_id=$request->store;
        $content->type=$request->type;
        $content->details=$content_name;
        $content->note=$request->note;
        $content->save();
        Session::flash('message', 'Saved !');
        return back();
    }

    public function contentdelete($id)
    {
        Content::find($id)->delete();
        Session::flash('message', 'Successfully Deleted !');
        return back();
    }
    public function contentdetails(Request $request)
    {
        $content=Content::find($request->id);
        $data['id']=$content->id;
        $data['storename']=$content->store->name;
        $data['type']=$content->type;
        $data['name']=$content->name;
        $data['details']=$content->details;
        $data['note']=$content->note;
        return $data;
    }
    public function updatecontent(Request $request)
    { 
        if ($request->content && $request->type != 'Caption Writting') {
            $content_name = time().'.'.$request->content->extension();
            $request->content->move(public_path('clientContent'), $content_name);

            $image_path = public_path()."/clientContent/".$request->oldContent;  // Value is not URL but directory file path

            if(File::exists($image_path)) {
                File::delete($image_path);
            }
        } else{
            $content_name = $request->oldContent;
        }

        if ($request->content && $request->type == 'Caption Writting') {
            $content_name = $request->content;
        }



        $content=Content::find($request->id);
        $content->name      = $request->name;
        $content->details   = $content_name;
        $content->note      = $request->note;
        $content->save();
        Session::flash('message', 'Updated !');
        return back();
    }
    public function content_correction()
    {
        $urls="digital";
        $lists=Digitalcontent::orderBy('updated_at', 'desc')->get();
        return view('superadmin.digital.content_correction', compact('urls', 'lists'));
    }
}
