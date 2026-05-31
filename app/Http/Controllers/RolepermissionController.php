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
use App\Models\Role;
use Auth;
use App\Models\Staff;
use App\Models\Toptool;
use App\Models\Activitylog;
use App\Http\Traits\ActivityLogTraits;


class RolepermissionController extends Controller
{
    use ActivityLogTraits;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $roless = $this->checkrole();
        if (isset($roless) && $roless == "1" || Auth::user()->type == 'admin' || Auth::user()->type == 'dropshipper') {
            $urls = "staff";
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
            $toptool = Toptool::where('name', 'Role and Permission')->where('uid', $user)->where('store_id', $store_id)->first();
            if (isset($toptool)) {
                $toptool->count = $toptool->count + 1;
                $toptool->save();
            } else {
                $toptool = new Toptool();
                $toptool->name = "Role and Permission";
                $toptool->image = "permissions.png";
                $toptool->url = "/role-and-permission";
                $toptool->count = "1";
                $toptool->uid = $user;
                $toptool->store_id = $store_id;
                $toptool->customer_id = $customer_id;
                $toptool->creator = $user;
                $toptool->editor = $user;
                $toptool->save();
            }
            $store_id = $store_id;
            $roles = Role::where('store_id', $store_id)->get();
            $activity = " Access Role List Page";
            $this->saveactivity($activity);
            return view('admin.role.index')->with('urls', $urls)->with('store_id', $store_id)->with('roles', $roles);
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
                    if ($pr == 'branch') {
                        $branch = 1;
                    } elseif ($pr == 'product') {
                        $product = 1;
                    } elseif ($pr == 'category') {
                        $category = 1;
                    } elseif ($pr == 'subcategory') {
                        $subcategory = 1;
                    } elseif ($pr == 'brand') {
                        $brand = 1;
                    } elseif ($pr == 'attribute') {
                        $attribute = 1;

                    } elseif ($pr == 'supplier') {
                        $supplier = 1;
                    } elseif ($pr == 'collection') {
                        $collection = 1;
                    } elseif ($pr == 'global_tab') {
                        $global_tab = 1;
                    } elseif ($pr == 'coupon') {
                        $coupon = 1;
                    } elseif ($pr == 'campaign') {
                        $campaign = 1;
                    } elseif ($pr == 'offer') {
                        $offer = 1;
                    } elseif ($pr == 'slider') {
                        $slider = 1;
                    } elseif ($pr == 'banner') {
                        $banner = 1;
                    } elseif ($pr == 'layouts') {
                        $layouts = 1;
                    } elseif ($pr == 'template') {
                        $template = 1;
                    } elseif ($pr == 'header') {
                        $header = 1;
                    } elseif ($pr == 'homepage') {
                        $homepage = 1;
                    } elseif ($pr == 'footer') {
                        $footer = 1;
                    } elseif ($pr == 'mobilemenu') {
                        $mobilemenu = 1;
                    } elseif ($pr == 'product_display') {
                        $product_display = 1;
                    } elseif ($pr == 'product_grid') {
                        $product_grid = 1;
                    } elseif ($pr == 'shop_page') {
                        $shop_page = 1;
                    } elseif ($pr == 'pages') {
                        $pages = 1;
                    } elseif ($pr == 'customer') {
                        $customer = 1;
                    } elseif ($pr == 'staff') {
                        $staff = 1;
                    } elseif ($pr == 'invoice') {
                        $invoice = 1;
                    } elseif ($pr == 'setting') {
                        $setting = 1;
                    } elseif ($pr == 'role_permission') {
                        $role_permission = 1;
                        return $role_permission;
                    } elseif ($pr == 'pos') {
                        $pos = 1;
                    } else {

                    }
                }
            }
        }
    }

    public function save(Request $request)
    {
        $rules = array(
            'namess' => 'required'
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator);
        } else {
            $role = new Role();
            $role->name = $request->namess;
            $permission = implode(',', $request->permission);
            $role->permission = $permission;
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
            $store_id = $store_id;
            $role->uid = $user;
            $role->customer_id = $customer_id;
            $role->store_id = $store_id;
            $role->creator = $user;
            $role->editor = $user;
            $role->save();
            $activity = " Save Role " . $role->name;
            $this->saveactivity($activity);
            Session()->flash('success_message', 'Role Created Successfully');
            return back();
        }
    }

    public function getname(Request $request)
    {
        $data = Role::find($request->id);
        return response()->json($data);
    }

    public function updaterole(Request $request)
    {
        $role = Role::find($request->id);
        $role->name = $request->name;
        $role->save();
        Session::flash('message', 'Role Update Successfully');
        return back();
    }

    public function edit(Request $request, $id)
    {
        $roless = $this->checkrole();
        if (isset($roless) && $roless == "1" || Auth::user()->type == 'admin' || Auth::user()->type == 'dropshipper') {
            $urls = "staff";
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
            $toptool = Toptool::where('name', 'Role and Permission')->where('uid', $user)->where('store_id', $store_id)->first();
            if (isset($toptool)) {
                $toptool->count = $toptool->count + 1;
                $toptool->save();
            } else {
                $toptool = new Toptool();
                $toptool->name = "Role and Permission";
                $toptool->image = "permissions.png";
                $toptool->url = "/role-and-permission";
                $toptool->count = "1";
                $toptool->uid = $user;
                $toptool->store_id = $store_id;
                $toptool->customer_id = $customer_id;
                $toptool->creator = $user;
                $toptool->editor = $user;
                $toptool->save();
            }
            $store_id = $store_id;
            $id = decrypt($id);
            $role = Role::find($id);
            $roles = Role::where('store_id', $store_id)->get();
            $activity = " Edit Role " . $role->name;
            $this->saveactivity($activity);
            return view('admin.role.edit')->with('urls', $urls)->with('store_id', $store_id)->with('role', $role)->with('roles', $roles);
        }
    }

    public function update(Request $request, $id)
    {
        $rules = array(
            'name' => 'required'
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator);
        } else {
            $role = Role::find($id);
            $role->name = $request->name;

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
            $store_id = $store_id;
            $role->uid = $user;
            $role->customer_id = $customer_id;
            $role->store_id = $store_id;
            $role->creator = $user;
            $role->editor = $user;
            $role->save();
            $activity = " Update Role " . $role->name;
            $this->saveactivity($activity);
            Session()->flash('success_message', 'Role Updated Successfully');
            return redirect()->route('admin.role.permission');
        }
    }

    public function permission($id)
    {
        $roless = $this->checkrole();
        if (isset($roless) && $roless == "1" || Auth::user()->type == 'admin' || Auth::user()->type == 'dropshipper') {
            $urls = "staff";
            $id = decrypt($id);
            $role = Role::find($id);
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
            $toptool = Toptool::where('name', 'Role and Permission')->where('uid', $user)->where('store_id', $store_id)->first();
            if (isset($toptool)) {
                $toptool->count = $toptool->count + 1;
                $toptool->save();
            } else {
                $toptool = new Toptool();
                $toptool->name = "Role and Permission";
                $toptool->image = "permissions.png";
                $toptool->url = "/role-and-permission";
                $toptool->count = "1";
                $toptool->uid = $user;
                $toptool->store_id = $store_id;
                $toptool->customer_id = $customer_id;
                $toptool->creator = $user;
                $toptool->editor = $user;
                $toptool->save();
            }
            $activity = " Access Permission List Page";
            $this->saveactivity($activity);
            return view('admin.role.permission')->with('role', $role)->with('urls', $urls);
        }
    }

    public function savepermission(Request $request, $id)
    {
        // dd($request->all());
        $role = Role::find($id);
        $permission = implode(',', $request->permission);
        // dd($permission);
        $role->permission = $permission;
        $role->save();
        $activity = " Save Permission into Role";
        $this->saveactivity($activity);
        Session()->flash('success_message', 'Permission Updated Successfully');
        return redirect()->route('admin.role.permission');
    }

    public function changerolessstatus(Request $request)
    {
        if ($request->text2 == '') {
            Session::flash('message', 'Please Select Role');
            return back();
        }
        if ($request->action == 'select') {
            Session::flash('message', 'Please Select a Option');
            return back();
        }
        if ($request->action == 'delete') {
            $id = explode(',', $request->text2);
            if (isset($id) && count($id) > 0) {
                foreach ($id as $ids) {
                    $product = Role::find($ids);
                    $product->delete();
                }
            }
            $activity = " Delete Role";
            $this->saveactivity($activity);
            Session::flash('message', 'Successfully Deleted Role');
            return back();
        }
    }

    public function delete($id)
    {
        $roless = $this->checkrole();
        if (isset($roless) && $roless == "1" || Auth::user()->type == 'admin' || Auth::user()->type == 'dropshipper') {
            $role = Role::find($id);
            $activity = " Delete Role " . $role->name;
            $this->saveactivity($activity);
            $role->delete();

            Session()->flash('success_message', 'Role Deleted Successfully');
            return back();
        }
    }
}
