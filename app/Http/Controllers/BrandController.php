<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Validator;
use Session;
use App\Http\Traits\ActivityLogTraits;
use Auth;
use App\Models\Activitylog;
use App\Models\Brand;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Role;
use App\Models\Staff;
use App\Models\Toptool;


class BrandController extends Controller
{
    use ActivityLogTraits;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $brand = $this->checkrole();
        if (isset($brand) && $brand == '1' || Auth::user()->type == 'admin' || Auth::user()->type == 'dropshipper') {
            $urls = "product";
            $user = Auth::user()->id;

            $userData = getUserData();
            $store_id = $userData['store_id'];
            $customer_id = $userData['customer_id'];

            $toptool = Toptool::where('name', 'Brand')->where('uid', $user)->where('store_id', $store_id)->first();
            if (isset($toptool)) {
                $toptool->count = $toptool->count + 1;
                $toptool->save();
            } else {
                $toptool = new Toptool();
                $toptool->name = "Brand";
                $toptool->image = "brand.png";
                $toptool->url = "/brand";
                $toptool->count = "1";
                $toptool->uid = $user;
                $toptool->store_id = $store_id;
                $toptool->customer_id = $customer_id;
                $toptool->creator = $user;
                $toptool->editor = $user;
                $toptool->save();
            }
            $activity = " Access Brand List Page";
            $this->saveactivity($activity);
            $brands = Brand::where('store_id', $store_id)->get();
            return view('admin.brand.index')->with('brands', $brands)->with('urls', $urls);
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
                        return $brand;
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
                    } elseif ($pr == 'pos') {
                        $pos = 1;
                    } else {
                    }
                }
            }
        } elseif (Auth::user()->type == 'superstaff') {
            $superstaff = DB::table('superstaffs')
                ->where('uid', Auth::user()->id)
                ->first();
            $superrole = DB::table('superroles')
                ->where('id', $superstaff->role_id)
                ->first();

            $permission = explode(',', $superrole->permission);

            if (isset(Auth::user()->store_id) && !is_null(Auth::user()->store_id)) {
                $superrolePermission = DB::table('superstaff_permissions')
                    ->where('role_id', $superstaff->role_id)
                    ->first();
                $superPermission = explode(',', $superrolePermission->permission);

                // Merge both permission arrays
                $permission = array_merge($superPermission, $permission);
            }
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
                    return $brand;
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
                } elseif ($pr == 'pos') {
                    $pos = 1;
                } else {
                    return false;
                }
            }

        }

        return false;
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
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator);
        } else {
            $brand = new Brand;
            $brand->name = $request->name;
            if (isset($request->image) && !empty($request->image)) {
//                $imageName = Carbon::now()->timestamp . '.' . $request->image->extension();
//                $request->image->storeAs('brand', $imageName);
                $brand->image = getLibraryImagePath($request->image);
            }
            $user = Auth::user()->id;

            $userData = getUserData();
            $store_id = $userData['store_id'];
            $customer_id = $userData['customer_id'];

            $brand->uid = $user;
            $brand->customer_id = $customer_id;
            $brand->store_id = $store_id;
            $brand->creator = $user;
            $brand->editor = $user;
            $brand->save();
            $activity = " Save Brand " . $brand->name;
            $this->saveactivity($activity);
            Session::flash('success_message', 'Brand Save Successfully !');
            return redirect('brand');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function brandexport(Request $request)
    {
        $date = Carbon::now();

        $userData = getUserData();
        $store_id = $userData['store_id'];

        $fileName = 'brand(' . $date . ').csv';
        $brand = Brand::where('store_id', $store_id)->get();

        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );

        $columns = array('Name', 'Image', 'Created_at');

        $callback = function () use ($brand, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($brand as $br) {
                $row['Name'] = $br->name;
                $row['Image'] = $br->image;
                $row['Create Date'] = $br->created_at;

                fputcsv($file, array($row['Name'], $row['Image'], $row['Create Date']));
            }

            fclose($file);
        };
        $activity = " Export Brand";
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
        $brand = $this->checkrole();
        if (isset($brand) && $brand == '1' || Auth::user()->type == 'admin' || Auth::user()->type == 'dropshipper') {
            $urls = "product";
            $user = Auth::user()->id;

            $userData = getUserData();
            $store_id = $userData['store_id'];
            $customer_id = $userData['customer_id'];

            $toptool = Toptool::where('name', 'Brand')->where('uid', $user)->where('store_id', $store_id)->first();
            if (isset($toptool)) {
                $toptool->count = $toptool->count + 1;
                $toptool->save();
            } else {
                $toptool = new Toptool();
                $toptool->name = "Brand";
                $toptool->image = "brand.png";
                $toptool->url = "/brand";
                $toptool->count = "1";
                $toptool->uid = $user;
                $toptool->store_id = $store_id;
                $toptool->customer_id = $customer_id;
                $toptool->creator = $user;
                $toptool->editor = $user;
                $toptool->save();
            }
            $brand = Brand::where('store_id', $store_id)->where('id', $id)->first();
            if (empty($brand)) {
                return back();
            }
            $activity = " Access Brand Edit Page " . $brand->name;
            $this->saveactivity($activity);
            return view('admin.brand.edit')->with('brand', $brand)->with('urls', $urls);
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
        $userData = getUserData();
        $store_id = $userData['store_id'];

        $rules = array(
            'name' => 'required'
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator);
        } else {
            $brand = Brand::where('store_id', $store_id)->where('id', $id)->first();
            if (empty($brand)) {
                return back();
            }
            $brand->name = $request->name;
            if (isset($request->image) && !empty($request->image)) {
//                $imageName = Carbon::now()->timestamp . '.' . $request->image->extension();
//                $request->image->storeAs('brand', $imageName);
                $brand->image = getLibraryImagePath($request->image);
            }
            $user = Auth::user()->id;
            $user_type = Auth::user()->type;
            if ($user_type == "admin" || $user_type == "dropshipper") {
                $customer = Customer::where('uid', $user)->first();
                $store_id = $customer->active_store;
                $customer_id = $customer->id;
            } elseif ($user_type == 'staff') {
                $staff = Staff::where('uid', $user)->first();
                $store_id = $staff->store_id;
                $customer_id = $staff->customer_id;
            }
            $brand->editor = $user;
            $brand->save();
            $activity = " Update Brand " . $brand->name;
            $this->saveactivity($activity);
            Session::flash('success_message', 'Brand Update Successfully !');
            return redirect('brand');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function deletebrand($id)
    {
        $userData = getUserData();
        $store_id = $userData['store_id'];

        $brand = Brand::where('store_id', $store_id)->where('id', $id)->first();
        if (empty($brand)) {
            return back();
        }

        $activity = " Delete Brand " . $brand->name;
        $this->saveactivity($activity);
        $brand->delete();
        Session::flash('success_message', 'Brand Delete Successfully !');
        return redirect('brand');
    }

    public function deleteImage($id)
    {
        $userData = getUserData();
        $store_id = $userData['store_id'];

        $brand = Brand::where('store_id', $store_id)->where('id', $id)->first();
        if (empty($brand)) {
            return sendError("Brand not found");
        }
        $brand->image = null;
        $brand->update();

        $activity = " Delete Brand Image Successfully";
        $this->saveactivity($activity);
        return sendResponse("Brand Image Deleted Successfully");
    }

    public function destroy($id)
    {
        $userData = getUserData();
        $store_id = $userData['store_id'];

        $brand = $this->checkrole();
        if (isset($brand) && $brand == '1' || Auth::user()->type == 'admin' || Auth::user()->type == 'dropshipper') {
            $brand = Brand::where('store_id', $store_id)->where('id', $id)->first();
            if (empty($brand)) {
                return back();
            }

            $brand->delete();
            Session::flash('success_message', 'Brand Delete Successfully !');
            return redirect('brand');
        }
    }

    public function changebrandstatus(Request $request)
    {
        if ($request->text2 == '') {
            Session::flash('message', 'Please Select Brand');
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
                    $product = Brand::find($ids);
                    $product->delete();
                }
            }
            $activity = " Delete Brand";
            $this->saveactivity($activity);
            Session::flash('message', 'Successfully Deleted Brand');
            return back();
        }
    }

    public function brandproduct($id)
    {
        $userData = getUserData();
        $store_id = $userData['store_id'];

        $brand = Brand::where('store_id', $store_id)->where('id', $id)->first();
        if (empty($brand)) {
            return back();
        }

        $products = Product::where('brand', $brand->id)->where('status', '!=', 'RecycleBin')->get();
        return view('admin.brand.product', ["brand" => $brand, "products" => $products]);
    }

    public function branddatte(Request $request, $id)
    {
        $userData = getUserData();
        $store_id = $userData['store_id'];

        $brand = Brand::where('store_id', $store_id)->where('id', $id)->first();
        if (empty($brand)) {
            return back();
        }

        $from = $request->formdate;
        $to = $request->enddate;;
        $products = Product::where('brand', $brand->id)->where('created_at', '>=', $from . ' 00:00:00')->where('created_at', '<=', $to . ' 23:59:59')->where('status', '!=', 'RecycleBin')->get();
        return view('admin.brand.product', compact('brand', 'products'));
    }
}
