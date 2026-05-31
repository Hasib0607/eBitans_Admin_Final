<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Validator;
use Session;
use Auth;
use App\Models\Store;
use App\Models\Staff;
use App\Models\Role;
use App\Models\Customer;
use App\Models\Toptool;
use App\Models\Activitylog;
use App\Http\Traits\ActivityLogTraits;
use App\Models\Plan;

class SubCategoryController extends Controller
{
    use ActivityLogTraits;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $subcategory = $this->checkrole();
        if (isset($subcategory) && $subcategory == '1' || Auth::user()->type == 'admin' || Auth::user()->type == 'dropshipper') {
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
            $toptool = Toptool::where('name', 'Subcategory')->where('uid', $user)->where('store_id', $store_id)->first();
            if (isset($toptool)) {
                $toptool->count = $toptool->count + 1;
                $toptool->save();
            } else {
                $toptool = new Toptool();
                $toptool->name = "Subcategory";
                $toptool->image = "subcategory.png";
                $toptool->url = "/subcategory";
                $toptool->count = "1";
                $toptool->uid = $user;
                $toptool->store_id = $store_id;
                $toptool->customer_id = $customer_id;
                $toptool->creator = $user;
                $toptool->editor = $user;
                $toptool->save();
            }

            $activity = "user " . $user . " Access Subcategory Page";
            $this->saveactivity($activity);

            $subcatgories = Category::where('parent', '!=', '0')
                ->where('store_id', $store_id)
                ->orderBy('position', 'ASC')
                ->get();

            // Get product counts grouped by subcategory IDs (exploded from comma-separated string)
            $subProductCounts = Product::where('store_id', $store_id)
                ->select('subcategory')
                ->get()
                ->flatMap(function ($product) {
                    return explode(',', $product->subcategory); // return flat list
                })
                ->map(function ($id) {
                    return (int)$id; // cast to integer for safety
                })
                ->countBy();

            // Assign product count to each subcategory
            $subcatgories->transform(function ($subcategory) use ($subProductCounts) {
                $subcategory->total_products = $subProductCounts[$subcategory->id] ?? 0;
                return $subcategory;
            });

            $parentCategoryIds = $subcatgories->pluck('parent')->unique();
            $parentCategories = Category::whereIn('id', $parentCategoryIds)->pluck('name', 'id');

            return view('admin.subcategory.index')->with('subcatgories', $subcatgories)->with('parentCategories', $parentCategories)->with('urls', $urls)->with('store_id', $store_id);
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
                        return $subcategory;
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
                    } elseif ($pr == 'pos') {
                        $pos = 1;
                    } else {

                    }
                }
            }
        }
    }

    public function changesubcatstatus(Request $request)
    {
        $user = Auth::user()->id;
        $user_type = Auth::user()->type;
        if ($user_type == "admin" || $user_type == "dropshipper") {
            $customer = Customer::where('uid', $user)->first();
            $store_id = $customer->active_store;
        } elseif ($user_type == 'staff') {
            $staff = Staff::where('uid', Auth::user()->id)->first();
            $store_id = $staff->store_id;
        }
        $store_id = $store_id;
        $catgories = Category::where('id', $request->id)->first();
        if (isset($catgories) && $catgories->status == 'active') {
            $catgories->status = 'inactive';
        } else {
            $catgories->status = 'active';
        }
        $catgories->save();
        $data = $catgories;
        $activity = "user " . $user . " Change Subcategory Status " . $catgories->name;
        $this->saveactivity($activity);
        return response()->json($data);
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
            'icon' => 'required',
            'position' => 'required'
        );
        $message = array(
            'name.required' => 'Category Name is required.',
            'icon.required' => 'Icon is required.',
            'position.required' => 'Position is required.',
        );

        $validator = Validator::make($request->all(), $rules, $message);
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator);
        } else {
            $user = Auth::user()->id;
            $user_type = Auth::user()->type;
            if ($user_type == "admin" || $user_type == "dropshipper") {
                $customer = Customer::where('uid', $user)->first();
            }
            $store = Store::where('id', $customer->active_store)->first();

            $plan = Plan::find($store->plan_id);
            $category = Category::where('store_id', $store->id)->where('parent', '!=', 0)->count();


            if ($plan->sub_category <= $category) {
                return back()->with('warning', 'Your Sub Category add Limit up');
            }


            $category = new Category;
            $category->name = $request->name;
            $category->parent = $request->parent;
            if (isset($request->icon) && $request->icon != 'null') {
                // $imageName="i".Carbon::now()->timestamp.'.'.$request->icon->extension();
                // $request->icon->storeAs('category',$imageName);
                // $category->icon=$imageName;
                $category->icon = $request->icon;
            }

            if ($request->input('banner')) {
//                $image = $request->file('banner');
//                $validation = imageValidation($image, $store->id);
//                if ($validation) {
//                    return back()->with('warning', $validation);
//                }
//
//                $imageUploadPath = 'assets/images/category/';
//                $imageName = uploadFile($image, $imageUploadPath);
                $category->banner = getLibraryImagePath($request->banner);
            }

            // if($request->status=='on'){
            //     $category->status='active';
            // }else{
            //     $category->status='inactive';
            // }
            $category->status = 'active';
            $category->position = $request->position;
            $category->uid = $user;
            $category->customer_id = $customer->id;
            $category->store_id = $customer->active_store;
            $category->creator = $user;
            $category->editor = $user;
            $category->save();

            $activity = " Save Subcategory " . $category->name;
            $this->saveactivity($activity);
            Session::flash('message', 'Subcategory Save Successfully !');
            return redirect('subcategory');
        }
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
        $subcategory = $this->checkrole();
        if (isset($subcategory) && $subcategory == '1' || Auth::user()->type == 'admin' || Auth::user()->type == 'dropshipper') {
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
            $toptool = Toptool::where('name', 'Subcategory')->where('uid', $user)->where('store_id', $store_id)->first();
            if (isset($toptool)) {
                $toptool->count = $toptool->count + 1;
                $toptool->save();
            } else {
                $toptool = new Toptool();
                $toptool->name = "Subcategory";
                $toptool->image = "subcategory.png";
                $toptool->url = "/subcategory";
                $toptool->count = "1";
                $toptool->uid = $user;
                $toptool->store_id = $store_id;
                $toptool->customer_id = $customer_id;
                $toptool->creator = $user;
                $toptool->editor = $user;
                $toptool->save();
            }
            $store_id = $store_id;

            $category = Category::where('store_id', $store_id)->where('id', $id)->first();
            if (empty($category)) {
                return back();
            }
            $activity = " Edit SubCategory " . $category->name;
            $this->saveactivity($activity);
            return view('admin.subcategory.edit')->with('category', $category)->with('urls', $urls)->with('store_id', $store_id);
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
            'name' => 'required',
            'icon' => 'required',
            'position' => 'required'
        );
        $message = array(
            'name.required' => 'Category Name is required.',
            'icon.required' => 'Icon is required.',
            'position.required' => 'Position is required.',
        );

        $validator = Validator::make($request->all(), $rules, $message);
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator);
        } else {
            $user = Auth::user()->id;
            $user_type = Auth::user()->type;
            if ($user_type == "admin" || $user_type == "dropshipper") {
                $customer = Customer::where('uid', $user)->first();
            }
            $store = Store::where('id', $customer->active_store)->first();
            $category = Category::where('store_id', $store->id)->where('id', $id)->first();
            if (empty($category)) {
                return back();
            }

            $category->name = $request->name;
            $category->parent = $request->parent;
            if (isset($request->icon) && $request->icon != 'null') {
                // $imageName="i".Carbon::now()->timestamp.'.'.$request->icon->extension();
                // $request->icon->storeAs('category',$imageName);
                // $category->icon=$imageName;
                $category->icon = $request->icon;
            }

            if ($request->input('banner')) {
//                $image = $request->file('banner');
//                $validation = imageValidation($image, $store->id);
//                if ($validation) {
//                    return back()->with('warning', $validation);
//                }
//
//                $imageUploadPath = 'assets/images/category/';
//                $imageName = updateFile($image, $imageUploadPath, $category->banner);
                $category->banner = getLibraryImagePath($request->banner);
            }

            if ($request->status == 'on') {
                $category->status = 'active';
            } else {
                $category->status = 'inactive';
            }
            $category->position = $request->position;
            $category->editor = $user;
            $category->save();
            $activity = " Update SubCategory " . $category->name;
            $this->saveactivity($activity);
            Session::flash('message', 'Subcategory Update Successfully !');
            return redirect('subcategory');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function deletecat($id)
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
        $category = Category::where('store_id', $store_id)->where('id', $id)->first();
        if (empty($category)) {
            return back();
        }
        $activity = " Delete SubCategory " . $category->name;
        $this->saveactivity($activity);
        $category->delete();

        Session::flash('success_message', 'Subcategory Delete Successfully !');
        return redirect('subcategory');
    }

    public function deleteImage($id)
    {
        $userData = getUserData();
        $store_id = $userData['store_id'];

        $category = Category::where('store_id', $store_id)->where('id', $id)->first();
        if (!isset($category)) {
            return sendError("Subcategory not found");
        }
        $category->banner = null;
        $category->update();

        $activity = " Delete Subcategory Banner Successfully";
        $this->saveactivity($activity);
        return sendResponse("Subcategory Banner Deleteed Successfully");
    }

    public function destroy($id)
    {
        $subcategory = $this->checkrole();
        if (isset($subcategory) && $subcategory == '1' || Auth::user()->type == 'admin' || Auth::user()->type == 'dropshipper') {
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
            $category = Category::where('store_id', $store_id)->where('id', $id)->first();
            if (empty($category)) {
                return back();
            }
            $category->delete();
            Session::flash('message', 'Subcategory Delete Successfully !');
            return redirect('subcategory');
        }
    }
}
