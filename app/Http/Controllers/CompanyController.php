<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Page;
use App\Models\Company;
use Illuminate\Support\Str;
use Validator;
use Session;
use Auth;
use App\Models\Staff;
use App\Models\Role;
use App\Models\Customer;


class CompanyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $st = $this->checkrole();
        if (isset($st) && $st == "1" || Auth::user()->type == 'admin' || Auth::user()->type == 'dropshipper') {
            $urls = "settings";
            $list = Company::all();
            return view('admin.company.index')
                ->with('data', $list)->with('urls', $urls);
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
                        return $setting;
                    } elseif ($pr == 'role_permission') {
                        $role_permission = 1;
                    } elseif ($pr == 'pos') {
                        $pos = 1;
                    } else {

                    }
                }
            }
        }
    }

    public function create()
    {
        $st = $this->checkrole();
        if (isset($st) && $st == "1" || Auth::user()->type == 'admin' || Auth::user()->type == 'dropshipper') {
            $urls = "settings";
            return view('admin.company.create')->with('urls', $urls);
        }
    }

    public function store(Request $request)
    {
        $rules = array(
            'name' => 'required',
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            Session::flash('message', 'Name and Title Required');
            return redirect()->back()
                ->withErrors($validator);
        } else {
            $company = new Company();
            $company->name = $request->name;
            $company->email = $request->email;
            $company->phone = $request->phone;
            $company->activeplan = $request->activeplan;
            $company->save();
            Session::flash('message', 'Successfully created!');
            return redirect()->route('admin.company');
        }
    }

    public function edit($id)
    {
        $st = $this->checkrole();
        if (isset($st) && $st == "1" || Auth::user()->type == 'admin' || Auth::user()->type == 'dropshipper') {
            $urls = "settings";
            $singleData = Company::find($id);
            return view('admin.company.edit')
                ->with('singleData', $singleData)->with('urls', $urls);
        }
    }

    public function update(Request $request, $id)
    {
        $rules = array(
            'name' => 'required',
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            Session::flash('message', 'Name and Title Required');
            return redirect()->back()
                ->withErrors($validator);
        } else {
            $company = Company::find($id);
            $company->name = $request->name;
            $company->email = $request->email;
            $company->phone = $request->phone;
            $company->activeplan = $request->activeplan;
            $company->save();
            Session::flash('message', 'Successfully Updated!');
            return redirect()->route('admin.company');
        }
    }

    public function destroy($id)
    {
        $st = $this->checkrole();
        if (isset($st) && $st == "1" || Auth::user()->type == 'admin' || Auth::user()->type == 'dropshipper') {
            Company::find($id)->delete();
            Session::flash('success_message', 'Successfully Deleted!');
            return redirect('company');
        }
    }
}
