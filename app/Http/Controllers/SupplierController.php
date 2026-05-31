<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Traits\ActivityLogTraits;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Validator;
use Session;
use Auth;
use App\Models\Activitylog;
use App\Models\Brand;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Role;
use App\Models\Supplier;
use App\Models\Staff;
use App\Models\Store;
use App\Models\Toptool;


class SupplierController extends Controller
{
    use ActivityLogTraits;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $supplier = $this->checkrole();
        if (isset($supplier) && $supplier == '1' || Auth::user()->type == 'admin' || Auth::user()->type == 'dropshipper') {
            $urls = "product";
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
            $toptool = Toptool::where('name', 'Supplier')->where('uid', $user)->where('store_id', $store_id)->first();
            if (isset($toptool)) {
                $toptool->count = $toptool->count + 1;
                $toptool->save();
            } else {
                $toptool = new Toptool();
                $toptool->name = "Supplier";
                $toptool->image = "supplier.png";
                $toptool->url = "/supplier";
                $toptool->count = "1";
                $toptool->uid = $user;
                $toptool->store_id = $store_id;
                $toptool->customer_id = $customer_id;
                $toptool->creator = $user;
                $toptool->editor = $user;
                $toptool->save();
            }
            $store_id = $store_id;
            $activity = " Access Supplier List Page";
            $this->saveactivity($activity);
            $supplier = Supplier::where('store_id', $store_id)->get();
            return view('admin.supplier.index')->with('suppliers', $supplier)->with('urls', $urls);
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
                        return $supplier;
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
                    } elseif ($pr == 'pos') {
                        $pos = 1;
                    } else {

                    }
                }
            }
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = array(
            'name' => 'required',
            'phone' => 'required'
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator);
        } else {
            $supplier = new Supplier;
            $supplier->name = $request->name;
            $supplier->company_name = $request->company_name;
            $supplier->phone = $request->phone;
            $supplier->address = $request->address;
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
            $store_id = $store_id;
            $supplier->uid = $user;
            $supplier->customer_id = $customer_id;
            $supplier->store_id = $store_id;
            $supplier->creator = $user;
            $supplier->editor = $user;
            $supplier->save();
            $activity = " Save Supplier " . $supplier->name;
            $this->saveactivity($activity);
            Session::flash('success_message', 'Supplier Save Successfully !');
            return redirect('supplier');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function supplierexport(Request $request)
    {
        $date = Carbon::now();
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
        $fileName = 'supplier(' . $date . ').csv';
        $supplier = Supplier::where('store_id', $store_id)->get();

        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );

        $columns = array('Name', 'Company Name', 'Phone', 'Address', 'Created_at');

        $callback = function () use ($supplier, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($supplier as $cat) {
                $row['Name'] = $cat->name;
                $row['Company Name'] = $cat->company_name;
                $row['Phone'] = $cat->phone;
                $row['Address'] = $cat->address;
                $row['Create Date'] = $cat->created_at;

                fputcsv($file, array($row['Name'], $row['Company Name'], $row['Phone'], $row['Address'], $row['Create Date']));
            }

            fclose($file);
        };
        $activity = " Export Supplier";
        $this->saveactivity($activity);
        return response()->stream($callback, 200, $headers);
    }

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
        $supplier = $this->checkrole();
        if (isset($supplier) && $supplier == '1' || Auth::user()->type == 'admin' || Auth::user()->type == 'dropshipper') {
            $urls = "product";
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
            $toptool = Toptool::where('name', 'Supplier')->where('uid', $user)->where('store_id', $store_id)->first();
            if (isset($toptool)) {
                $toptool->count = $toptool->count + 1;
                $toptool->save();
            } else {
                $toptool = new Toptool();
                $toptool->name = "Supplier";
                $toptool->image = "supplier.png";
                $toptool->url = "/supplier";
                $toptool->count = "1";
                $toptool->uid = $user;
                $toptool->store_id = $store_id;
                $toptool->customer_id = $customer_id;
                $toptool->creator = $user;
                $toptool->editor = $user;
                $toptool->save();
            }
            $supplier = Supplier::where('store_id', $store_id)->where('id', $id)->first();
            if (empty($supplier)) {
                return back();
            }
            $activity = " Edit Supplier " . $supplier->name;
            $this->saveactivity($activity);
            return view('admin.supplier.edit')->with('supplier', $supplier)->with('urls', $urls);
        }
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
        $rules = array(
            'name' => 'required'
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator);
        } else {
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

            $supplier = Supplier::where('store_id', $store_id)->where('id', $id)->first();
            if (empty($supplier)) {
                return back();
            }
            $supplier->name = $request->name;
            $supplier->company_name = $request->company_name;
            $supplier->phone = $request->phone;
            $supplier->address = $request->address;

            $store_id = $store_id;
            $supplier->editor = $user;
            $supplier->save();
            $activity = " Supplier Update " . $supplier->name;
            $this->saveactivity($activity);
            Session::flash('success_message', 'Supplier Updated Successfully !');
            return redirect('supplier');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function deletesupplier($id)
    {
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

        $brand = Supplier::where('store_id', $store_id)->where('id', $id)->first();
        if (empty($brand)) {
            return back();
        }
        $activity = " Delete Supplier " . $brand->name;
        $this->saveactivity($activity);
        $brand->delete();
        Session::flash('success_message', 'Supplier Delete Successfully !');
        return redirect('supplier');
    }

    public function destroy($id)
    {
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

        $supplier = $this->checkrole();
        if (isset($supplier) && $supplier == '1' || Auth::user()->type == 'admin' || Auth::user()->type == 'dropshipper') {
            $brand = Supplier::where('store_id', $store_id)->where('id', $id)->first();
            if (empty($brand)) {
                return back();
            }
            $brand->delete();
            Session::flash('success_message', 'Supplier Delete Successfully !');
            return redirect('supplier');
        }
    }

    public function changesupplierstatus(Request $request)
    {
        if ($request->text2 == '') {
            Session::flash('message', 'Please Select Product');
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
                    $product = Supplier::find($ids);
                    $product->delete();
                }
            }
            $activity = " Delete Supplier";
            $this->saveactivity($activity);
            Session::flash('message', 'Successfully Deleted Supplier');
            return back();
        }
    }

    public function supplierproduct($id)
    {
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

        $supplier = Supplier::where('store_id', $store_id)->where('id', $id)->first();
        $supplier = Supplier::where('store_id', $store_id)->where('id', $id)->first();
        if (empty($supplier)) {
            return back();
        }

        $products = Product::where('supplier', $supplier->id)->where('status', '!=', 'RecycleBin')->get();
        return view('admin.supplier.product', compact('supplier', 'products'));
    }

    public function supplierdatte(Request $request, $id)
    {
        $from = $request->formdate;
        $to = $request->enddate;
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

        $supplier = Supplier::where('store_id', $store_id)->where('id', $id)->first();
        if (empty($supplier)) {
            return back();
        }
        $products = Product::where('supplier', $supplier->id)->where('created_at', '>=', $from . ' 00:00:00')->where('created_at', '<=', $to . ' 23:59:59')->where('status', '!=', 'RecycleBin')->get();
        return view('admin.supplier.product', compact('supplier', 'products'));
    }
}
