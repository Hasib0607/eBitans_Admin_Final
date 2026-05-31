<?php

namespace App\Http\Controllers;

use App\Models\PlanDetail;
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
use App\Http\Controllers\CheckroleController;
use App\Models\Staff;
use App\Models\Role;
use App\Models\Superrole;
use App\Models\Superstaff;
use App\Models\Posplan;
use App\Models\Digitalplan;
use Auth;

class PlanController extends Controller
{
    public function index()
    {
        $data = Plan::latest()->get();

        return view('admin.plans', compact('data'));
    }

    public function backplan()
    {
        $roless = $this->checkroleplan();
        if (isset($roless) && $roless == "1" || Auth::user()->type == 'superadmin') {
            $urls = "plans";
            $plans = Plan::orderBy('position', 'ASC')->get();
            return view('admin.super.plan.index')->with('urls', $urls)->with('plans', $plans);
        } else {
            return redirect()->route('superadmin.index');
        }
    }

    /**
     *
     * Display a listing of the resource.
     *
     * @return int|string|void
     */
    public function checkroleplan()
    {
        if (Auth::check()) {
            if (Auth::user()->type == 'superstaff') {
                $superstaff = Superstaff::where('uid', Auth::user()->id)->first();
                $superrole = Superrole::where('id', $superstaff->role_id)->first();
                $permissionss = explode(',', $superrole->permission);
                foreach ($permissionss as $key => $prs) {
                    if ($prs == 'plans') {
                        $plans = 1;
                        return $plans;
                    } else {

                    }
                }
            }
        } else {
            return route('login');
        }
    }

    public function posplan()
    {
        $roless = $this->checkroleplan();
        if (isset($roless) && $roless == "1" || Auth::user()->type == 'superadmin') {
            $urls = "plans";
            $plans = Posplan::orderBy('position', 'ASC')->get();
            return view('admin.super.posplan.index')->with('urls', $urls)->with('plans', $plans);
        } else {
            return redirect()->route('superadmin.index');
        }
    }

    public function digitalplan()
    {
        $roless = $this->checkroleplan();
        if (isset($roless) && $roless == "1" || Auth::user()->type == 'superadmin') {
            $urls = "plans";
            $plans = Digitalplan::orderBy('position', 'ASC')->get();
            return view('admin.super.digitalplan.index')->with('urls', $urls)->with('plans', $plans);
        } else {
            return redirect()->route('superadmin.index');
        }
    }

    public function plancreate()
    {
        $roless = $this->checkroleplan();
        if (isset($roless) && $roless == "1" || Auth::user()->type == 'superadmin') {
            $urls = "plans";
            return view('admin.super.plan.create')->with('urls', $urls);
        } else {
            return redirect()->route('superadmin.index');
        }
    }

    public function posplancreate()
    {
        $roless = $this->checkroleplan();
        if (isset($roless) && $roless == "1" || Auth::user()->type == 'superadmin') {
            $urls = "plans";
            return view('admin.super.posplan.create')->with('urls', $urls);
        } else {
            return redirect()->route('superadmin.index');
        }
    }

    public function digitalplancreate()
    {
        $roless = $this->checkroleplan();
        if (isset($roless) && $roless == "1" || Auth::user()->type == 'superadmin') {
            $urls = "plans";
            return view('admin.super.digitalplan.create')->with('urls', $urls);
        } else {
            return redirect()->route('superadmin.index');
        }
    }

    public function saveplan(Request $request)
    {
//          dd($request->all());
        $rules = array(
            'name' => 'required',
            'price' => 'required',
            'discount_type' => 'required',
            'position' => 'required'
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator);
        } else {
            $plan = new Plan;
            $plan->name = $request->name;
            $plan->subtitle = $request->subtitle;
            $plan->price = $request->price;
            $plan->discount_type = $request->discount_type;
            $plan->onedis = $request->onemdis;
            $plan->sixdis = $request->sixstmdis;
            $plan->twelvedis = $request->twelvestmdis;
            $plan->twentyfourdis = $request->twentyfourstmdis;
            $plan->usd_price = $request->usd_price;
            $plan->usd_discount_type = $request->usd_discount_type;
            $plan->usd_1_dis = $request->usd_1_dis;
            $plan->usd_6_dis = $request->usd_6_dis;
            $plan->usd_12_dis = $request->usd_12_dis;
            $plan->usd_24_dis = $request->usd_24_dis;
            $plan->branch = null;
            $plan->staff = $request->staff;
            $plan->product = $request->product;
            $plan->category = $request->category;
            $plan->sub_category = $request->sub_category;
            $plan->google_ad = $request->googlead;
            $plan->inventory = $request->inventory;
            $plan->advance_report = $request->advance_report;
            $plan->website_setup = $request->website_setup;
            $plan->order = $request->order;
            $plan->payment_processing_charge = $request->payment_processing_charge;
            $plan->monthly_chat_support = $request->monthly_chat_support ?? 0;
            $plan->upload_file_limit = $request->upload_file_limit ?? 0;
            $plan->position = $request->position;
            if ($request->status == "on") {
                $plan->status = "active";
            } else {
                $plan->status = "inactive";
            }
            $plan->save();
            Session::flash('succcess_message', 'Plan Save Successfully.');
            return redirect()->route('plans');
        }
    }

    public function saveposplan(Request $request)
    {
        //  dd($request->all());
        $rules = array(
            'name' => 'required',
            'price' => 'required',
            'discount_type' => 'required',
            'position' => 'required'
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator);
        } else {
            $plan = new Posplan;
            $plan->name = $request->name;
            $plan->subtitle = $request->subtitle;
            $plan->price = $request->price;
            $plan->usd_price = $request->usd_price;
            $plan->discount_type = $request->discount_type;
            $plan->onedis = $request->onemdis;
            $plan->sixdis = $request->sixstmdis;
            $plan->twelvedis = $request->twelvestmdis;
            $plan->twentyfourdis = $request->twentyfourstmdis;
            $plan->branch = $request->branch;
            $plan->staff = $request->staff;
            $plan->product = $request->product;
            $plan->inventory = $request->inventory;
            $plan->advance_report = $request->advance_report;
            $plan->pos_setup = $request->pos_setup;
            $plan->order = $request->order;
            $plan->payment_processing_charge = $request->payment_processing_charge;
            $plan->monthly_chat_support = $request->monthly_chat_support ?? 0;
            $plan->position = $request->position;
            if ($request->status == "on") {
                $plan->status = "active";
            } else {
                $plan->status = "inactive";
            }
            $plan->save();
            Session::flash('succcess_message', 'Pos Plan Save Successfully.');
            return redirect()->route('posplans');
        }
    }

    public function savedigitalplan(Request $request)
    {
        //  dd($request->all());
        $rules = array(
            'name' => 'required',
            'price' => 'required',
            'discount_type' => 'required',
            'position' => 'required'
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator);
        } else {
            $plan = new Digitalplan;
            $plan->name = $request->name;
            $plan->subtitle = $request->subtitle;
            $plan->price = $request->price;
            $plan->discount_type = $request->discount_type;
            $plan->onedis = $request->onemdis;
            $plan->sixdis = $request->sixstmdis;
            $plan->twelvedis = $request->twelvestmdis;
            $plan->twentyfourdis = $request->twentyfourstmdis;
            $plan->page_setup = $request->page_setup;
            $plan->static_content = $request->static_content;
            $plan->video_content = $request->video_content;
            $plan->gify_content = $request->gify_content;
            $plan->google_ad = $request->googlead;
            $plan->boosting_page = $request->boosting_page;
            $plan->caption_writting = $request->caption_writting;
            // $plan->branch=$request->branch;
            // $plan->staff=$request->staff;
            // $plan->product=$request->product;
            // $plan->inventory=$request->inventory;
            // $plan->advance_report=$request->advance_report;
            // $plan->pos_setup=$request->pos_setup;
            // $plan->order=$request->order;
            $plan->payment_processing_charge = $request->payment_processing_charge;
            $plan->monthly_chat_support = $request->monthly_chat_support ?? 0;
            $plan->position = $request->position;
            if ($request->status == "on") {
                $plan->status = "active";
            } else {
                $plan->status = "inactive";
            }
            $plan->save();
            Session::flash('succcess_message', 'Digital Marketing Plan Save Successfully.');
            return redirect()->route('digitalplans');
        }
    }

    public function editplan($id)
    {
        $roless = $this->checkroleplan();
        if (isset($roless) && $roless == "1" || Auth::user()->type == 'superadmin') {
            $urls = "plans";
            $data = Plan::find($id);
            $plan_details = PlanDetail::where('plan_id', $id)
                ->orderBy('type')
                ->get();

            return view('admin.super.plan.edit')->with('urls', $urls)->with('data', $data)->with('plan_details',
                $plan_details);
        } else {
            return redirect()->route('superadmin.index');
        }
    }

    public function editposplan($id)
    {
        $roless = $this->checkroleplan();
        if (isset($roless) && $roless == "1" || Auth::user()->type == 'superadmin') {
            $urls = "plans";
            $data = Posplan::find($id);
            return view('admin.super.posplan.edit')->with('urls', $urls)->with('data', $data);
        } else {
            return redirect()->route('superadmin.index');
        }
    }

    public function editdigitalplan($id)
    {
        $roless = $this->checkroleplan();
        if (isset($roless) && $roless == "1" || Auth::user()->type == 'superadmin') {
            $urls = "plans";
            $data = Digitalplan::find($id);
            return view('admin.super.digitalplan.edit')->with('urls', $urls)->with('data', $data);
        } else {
            return redirect()->route('superadmin.index');
        }
    }

    public function updateplan(Request $request, $id)
    {
        $rules = array(
            'name' => 'required',
            'price' => 'required',
            'discount_type' => 'required',
            'position' => 'required',
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator);
        } else {
            $plan = Plan::find($id);
            $plan->name = $request->name;
            $plan->subtitle = $request->subtitle;
            $plan->price = $request->price;
            $plan->discount_type = $request->discount_type;
            $plan->onedis = $request->onemdis;
            $plan->sixdis = $request->sixstmdis;
            $plan->twelvedis = $request->twelvestmdis;
            $plan->twentyfourdis = $request->twentyfourstmdis;
            $plan->usd_price = $request->usd_price;
            $plan->usd_discount_type = $request->usd_discount_type;
            $plan->usd_1_dis = $request->usd_1_dis;
            $plan->usd_6_dis = $request->usd_6_dis;
            $plan->usd_12_dis = $request->usd_12_dis;
            $plan->usd_24_dis = $request->usd_24_dis;
            $plan->branch = null;
            $plan->staff = $request->staff;
            $plan->product = $request->product;
            $plan->category = $request->category;
            $plan->sub_category = $request->sub_category;
            $plan->google_ad = $request->googlead;
            $plan->inventory = $request->inventory;
            $plan->advance_report = $request->advance_report;
            $plan->website_setup = $request->website_setup;
            $plan->order = $request->order;
            $plan->payment_processing_charge = $request->payment_processing_charge;
            $plan->monthly_chat_support = $request->monthly_chat_support ?? 0;
            $plan->upload_file_limit = $request->upload_file_limit ?? 0;
            $plan->position = $request->position;
            if ($request->status == "on") {
                $plan->status = "active";
            } else {
                $plan->status = "inactive";
            }
            $plan->save();
            $this->plan_details_update_create_delete($request, $id);


            Session::flash('succcess_message', 'Plan Update Successfully.');
            return redirect()->route('plans');
        }
    }

    private function plan_details_update_create_delete($request, $id)
    {
        $detailIds = [];
        $details = [];

        if (isset($request->details) && count($request->details) > 0) {
            // Get IDs from the details array
            $detailIds = array_column($request->details, 'id');

            // Modify status dynamically (convert 'ON' to true, else set false)
            $details = array_map(function ($detail) {
                // Ensure the 'status' key exists and handle its conversion
                $detail['status'] = isset($detail['status']) && $detail['status'] === 'on' ? true : false;
                return $detail;
            }, $request->details);
        }

        // Delete records not in the details array
        PlanDetail::where('plan_id', $id)
            ->whereNotIn('id', $detailIds)
            ->delete();

        // Perform the upsert if details are provided
        if (!empty($details)) {
            PlanDetail::upsert(
                $details,                   // Data to insert or update
                ['id'],                     // Unique columns to determine if the record exists
                ['title', 'position', 'type', 'status'] // Columns to update if the record exists
            );
        }
    }


    public function updateposplan(Request $request, $id)
    {
        $rules = array(
            'name' => 'required',
            'price' => 'required',
            'discount_type' => 'required',
            'position' => 'required'
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator);
        } else {
            $plan = Posplan::find($id);
            $plan->name = $request->name;
            $plan->subtitle = $request->subtitle;
            $plan->price = $request->price;
            $plan->usd_price = $request->usd_price;
            $plan->discount_type = $request->discount_type;
            $plan->onedis = $request->onemdis;
            $plan->sixdis = $request->sixstmdis;
            $plan->twelvedis = $request->twelvestmdis;
            $plan->twentyfourdis = $request->twentyfourstmdis;
            $plan->branch = $request->branch;
            $plan->staff = $request->staff;
            $plan->product = $request->product;
            $plan->inventory = $request->inventory;
            $plan->advance_report = $request->advance_report;
            $plan->pos_setup = $request->pos_setup;
            $plan->order = $request->order;
            $plan->payment_processing_charge = $request->payment_processing_charge;
            $plan->monthly_chat_support = $request->monthly_chat_support ?? 0;
            $plan->position = $request->position;
            if ($request->status == "on") {
                $plan->status = "active";
            } else {
                $plan->status = "inactive";
            }
            $plan->save();
            Session::flash('succcess_message', 'Pos Plan Update Successfully.');
            return redirect()->route('posplans');
        }
    }

    public function updatedigitalplan(Request $request, $id)
    {
        $rules = array(
            'name' => 'required',
            'price' => 'required',
            'discount_type' => 'required',
            'position' => 'required'
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator);
        } else {
            $plan = Digitalplan::find($id);
            $plan->name = $request->name;
            $plan->subtitle = $request->subtitle;
            $plan->price = $request->price;
            $plan->discount_type = $request->discount_type;
            $plan->onedis = $request->onemdis;
            $plan->sixdis = $request->sixstmdis;
            $plan->twelvedis = $request->twelvestmdis;
            $plan->twentyfourdis = $request->twentyfourstmdis;
            $plan->page_setup = $request->page_setup;
            $plan->static_content = $request->static_content;
            $plan->video_content = $request->video_content;
            $plan->gify_content = $request->gify_content;
            $plan->google_ad = $request->googlead;
            $plan->boosting_page = $request->boosting_page;
            $plan->caption_writting = $request->caption_writting;
            $plan->payment_processing_charge = $request->payment_processing_charge;
            $plan->monthly_chat_support = $request->monthly_chat_support ?? 0;
            $plan->position = $request->position;
            if ($request->status == "on") {
                $plan->status = "active";
            } else {
                $plan->status = "inactive";
            }
            $plan->save();
            Session::flash('succcess_message', 'Digital Plan Update Successfully.');
            return redirect()->route('digitalplans');
        }
    }

    public function deleteplan($id)
    {
        return redirect()->back();
//        $roless = $this->checkroleplan();
//        if (isset($roless) && $roless == "1" || Auth::user()->type == 'superadmin') {
//            $plan = Plan::find($id);
//            $plan->delete();
//            Session::flash('succcess_message', 'Plan Deleted Successfully.');
//            return redirect()->route('plans');
//        } else {
//            return redirect()->route('superadmin.index');
//        }
    }

    public function deleteposplan($id)
    {
        return redirect()->back();
//        $roless = $this->checkroleplan();
//        if (isset($roless) && $roless == "1" || Auth::user()->type == 'superadmin') {
//            $plan = Posplan::find($id);
//            $plan->delete();
//            Session::flash('succcess_message', 'Pos Plan Deleted Successfully.');
//            return redirect()->route('posplans');
//        } else {
//            return redirect()->route('superadmin.index');
//        }
    }

    public function deletedigitalplan($id)
    {
        return redirect()->back();
//        $roless = $this->checkroleplan();
//        if (isset($roless) && $roless == "1" || Auth::user()->type == 'superadmin') {
//            $plan = Digitalplan::find($id);
//            $plan->delete();
//            Session::flash('succcess_message', 'Digital Plan Deleted Successfully.');
//            return redirect()->route('digitalplans');
//        } else {
//            return redirect()->route('superadmin.index');
//        }
    }

    public function changeplansssstatus(Request $request)
    {
        if ($request->text2 == '') {
            Session::flash('error', 'Please Select Plan');
            return back();
        }
        if ($request->action == 'select') {
            Session::flash('error', 'Please Select a Option');
            return back();
        }

        if ($request->action == 'active') {
            $id = explode(',', $request->text2);
            if (isset($id) && count($id) > 0) {
                foreach ($id as $ids) {
                    $product = Plan::find($ids);
                    $product->status = 'active';
                    $product->save();
                }
            }
            Session::flash('message', 'Successfully Active Plan');
            return back();
        }
        if ($request->action == 'deactive') {
            $id = explode(',', $request->text2);
            if (isset($id) && count($id) > 0) {
                foreach ($id as $ids) {
                    $product = Plan::find($ids);
                    $product->status = 'deactive';
                    $product->save();
                }
            }
            Session::flash('message', 'Successfully Deactive Plan');
            return back();
        }
        if ($request->action == 'delete') {
            $id = explode(',', $request->text2);
            if (isset($id) && count($id) > 0) {
                foreach ($id as $ids) {
                    $product = Plan::find($ids);
                    $product->delete();
                }
            }
            Session::flash('message', 'Successfully Deleted Plan');
            return back();
        }
    }

    public function changeposplansssstatus(Request $request)
    {
        if ($request->text2 == '') {
            Session::flash('error', 'Please Select Plan');
            return back();
        }
        if ($request->action == 'select') {
            Session::flash('error', 'Please Select a Option');
            return back();
        }

        if ($request->action == 'active') {
            $id = explode(',', $request->text2);
            if (isset($id) && count($id) > 0) {
                foreach ($id as $ids) {
                    $product = Posplan::find($ids);
                    $product->status = 'active';
                    $product->save();
                }
            }
            Session::flash('message', 'Successfully Active Plan');
            return back();
        }
        if ($request->action == 'deactive') {
            $id = explode(',', $request->text2);
            if (isset($id) && count($id) > 0) {
                foreach ($id as $ids) {
                    $product = Posplan::find($ids);
                    $product->status = 'deactive';
                    $product->save();
                }
            }
            Session::flash('message', 'Successfully Deactive Plan');
            return back();
        }
        if ($request->action == 'delete') {
            $id = explode(',', $request->text2);
            if (isset($id) && count($id) > 0) {
                foreach ($id as $ids) {
                    $product = Posplan::find($ids);
                    $product->delete();
                }
            }
            Session::flash('message', 'Successfully Deleted Plan');
            return back();
        }
    }

    public function changedigitalplansssstatus(Request $request)
    {
        if ($request->text2 == '') {
            Session::flash('error', 'Please Select Plan');
            return back();
        }
        if ($request->action == 'select') {
            Session::flash('error', 'Please Select a Option');
            return back();
        }

        if ($request->action == 'active') {
            $id = explode(',', $request->text2);
            if (isset($id) && count($id) > 0) {
                foreach ($id as $ids) {
                    $product = Digitalplan::find($ids);
                    $product->status = 'active';
                    $product->save();
                }
            }
            Session::flash('message', 'Successfully Active Plan');
            return back();
        }
        if ($request->action == 'deactive') {
            $id = explode(',', $request->text2);
            if (isset($id) && count($id) > 0) {
                foreach ($id as $ids) {
                    $product = Digitalplan::find($ids);
                    $product->status = 'deactive';
                    $product->save();
                }
            }
            Session::flash('message', 'Successfully Deactive Plan');
            return back();
        }
        if ($request->action == 'delete') {
            $id = explode(',', $request->text2);
            if (isset($id) && count($id) > 0) {
                foreach ($id as $ids) {
                    $product = Digitalplan::find($ids);
                    $product->delete();
                }
            }
            Session::flash('message', 'Successfully Deleted Plan');
            return back();
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.create-plan');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
