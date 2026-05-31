<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Orderitem;
use App\Models\Order;
use App\Models\Customer;
use App\Models\Staff;
use App\Models\Activitylog;
use App\Models\Activity;
use App\Models\Role;
use Session;
use Auth;
use Carbon\Carbon;
use App\Models\Toptool;

class ActivityLogController extends Controller
{
    public function index()
    {
        $activity_log = $this->checkrole();
        if (isset($activity_log) && $activity_log == '1' || Auth::user()->type == 'admin' || Auth::user()->type == 'dropshipper') {
            $urls = "addons";
            if (Auth::user()->type == 'admin' || Auth::user()->type == 'staff') {
                $user = Auth::user()->id;
                $user_type = Auth::user()->type;
                if ($user_type == 'admin' || $user_type == 'dropshipper') {
                    $customer = Customer::where('uid', $user)->first();
                    $store_id = $customer->active_store;
                    $customer_id = $customer->id;
                } elseif ($user_type == 'staff') {
                    $staff = Staff::where('uid', $user)->first();
                    $store_id = $staff->store_id;
                    $customer_id = $staff->customer_id;
                }
                $toptool = Toptool::where('name', 'ActivityLog')->where('uid', $user)->where('store_id', $store_id)->first();
                if (isset($toptool)) {
                    $toptool->count = $toptool->count + 1;
                    $toptool->save();
                } else {
                    $toptool = new Toptool();
                    $toptool->name = "ActivityLog";
                    $toptool->image = "ecommerce.png";
                    $toptool->url = "/activitylog";
                    $toptool->count = "1";
                    $toptool->uid = $user;
                    $toptool->store_id = $store_id;
                    $toptool->customer_id = $customer_id;
                    $toptool->creator = $user;
                    $toptool->editor = $user;
                    $toptool->save();
                }
                $data = Activitylog::where('store_id', $store_id)->orderBy('id', 'DESC')->get();
            }
            $active = Activity::where('store_id', $store_id)->whereDate('expiry_date', '>=', Carbon::now())->first();
            if (isset($active)) {
                return view('admin.activitylog.index')->with('urls', $urls)->with('data', $data);
            } else {
                return redirect()->route('admin.index');
            }
        } else {
            return redirect()->route('admin.index');
        }

    }

    public function checkrole()
    {

        if (Auth::user()->type == 'staff') {
            $staff = Staff::where('uid', Auth::user()->id)->first();
            $store_id = $staff->store_id;
            $role = Role::where('id', $staff->role_id)->first();
            if (isset($role)) {

                $permission = explode(',', $role->permission);
                foreach ($permission as $key => $pr) {
                    if ($pr == 'activity_log') {
                        $activity_log = 1;
                    } else {

                    }
                }
            }
        }
    }

    public function saveactivity($activity, $store_id)
    {
        $act = new Activitylog();
        $act->uid = Auth::user()->id;
        $act->ip = $_SERVER['REMOTE_ADDR'];
        $act->activity = $activity;
        $act->is_superadmin = false;
        $act->store_id = $store_id;
        $act->save();
        return $act;
    }

    public function deleteall(Request $request)
    {
        if ($request->text2 == '') {
            Session::flash('error', 'Please Select At Least One Item');
            return back();
        }
        if ($request->action == 'select') {
            Session::flash('error', 'Please Select a Option');
            return back();
        }
        if ($request->action == 'delete') {
            $id = explode(',', $request->text2);
            if (isset($id) && count($id) > 0) {
                foreach ($id as $ids) {
                    $product = Activitylog::find($ids);
                    $product->delete();
                }
            }
            Session::flash('message', 'Successfully Deleted');
            return back();
        }
    }

    public function datefilter(Request $request)
    {
        $urls = "addons";
        $user = Auth::user()->id;
        $user_type = Auth::user()->type;
        if ($user_type == "admin" || $user_type == "dropshipper") {
            $customer = Customer::where('uid', $user)->first();
            $store_id = $customer->active_store;
            $customer_id = $customer->id;
        } elseif ($user_type == 'staff') {
            $staff = Staff::where('uid', Auth::user()->id)->first();
            $store_id = $staff->store_id;
            $customer_id = $staff->customer_id;
        }
        $from = $request->formdate;
        $to = $request->enddate;
        $data = Activitylog::whereBetween('created_at', [$from, $to])->where('store_id', $store_id)->get();
        return view('admin.activitylog.index')->with('urls', $urls)->with('data', $data);
    }
}
