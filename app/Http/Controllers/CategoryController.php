<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Cache;
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
use App\Models\Product;
use App\Http\Traits\ActivityLogTraits;
use App\Models\Activitylog;
use Illuminate\Support\Facades\DB;
use App\Models\Plan;

class CategoryController extends Controller
{
    use ActivityLogTraits;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if (canAccess('category') || Auth::user()->type == 'admin' || Auth::user()->type == 'dropshipper') {
            $urls = "category";

            /*extract user_id, user_type, store_id, customer_id*/
            extract(getUserData());

            /*increment tools count*/
            topToolsCount('Category', "categories.png", "/category");

            $activity = "Access Category List Page";
            $this->saveactivity($activity);

            $catgories = Category::where('parent', 0)
                ->where('store_id', $store_id)
                ->where('status', '!=', 'RecycleBin')
                ->orderBy('position', 'ASC')
                ->get();

            $productCounts = Product::where('store_id', $store_id)
                ->get()
                ->flatMap(function ($product) {
                    return explode(',', $product->category);
                })
                ->countBy();

            $catgories->transform(function ($category) use ($productCounts) {
                $category->total_products = $productCounts[$category->id] ?? 0;
                return $category;
            });

            return view('admin.category.index')->with('catgories', $catgories)->with('urls', $urls);
        }

        return redirect()->back();
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
                        return $category;
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
                    } elseif ($pr == 'pos') {
                        $pos = 1;
                    } else {

                    }
                }
            }
        }
    }

    public function updateposition(Request $request)
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
        $value = $request->value;
        $id = $request->id;
        $test = Category::where('store_id', $store_id)->where('id', $id)->first();

        if (empty($test)) {
            return back();
        }
        $test->position = $value;
        $test->save();
        // $data=$test;
        $data = $test;

        $activity = "Update Category Position " . $test->name;
        $this->saveactivity($activity);
        return response()->json($data);
    }

    public function suggest(Request $request)
    {
        $query = $request->input('q');

        $cacheKey = 'category_suggestions_' . md5($query);

        $suggestions = Cache::remember($cacheKey, 60, function () use ($query) {
            return Category::select('name')
                ->where('name', 'like', "%{$query}%")
                ->distinct()
                ->limit(10)
                ->pluck('name');
        });

        return response()->json($suggestions);
    }

    public function updatePositionProduct(Request $request)
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

        $value = $request->value;
        $id = $request->id;
        $test = Product::where('store_id', $store_id)->where('id', $id)->first();
        if (empty($test)) {
            return back();
        }
        $test->position = $value;
        $test->save();
        // $data=$test;
        $data = $test;
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
        $activity = "Update Category Position " . $test->name;
        $this->saveactivity($activity);
        return response()->json($data);
    }


    public function changecatstatus(Request $request)
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
        $catgories = Category::where('store_id', $store_id)->where('id', $request->id)->first();
        if (empty($catgories)) {
            return back();
        }
        if (isset($catgories) && $catgories->status == 'active') {
            $catgories->status = 'inactive';
        } else {
            $catgories->status = 'active';
        }
        $catgories->save();

        $activity = "Change Category Status " . $catgories->name;
        $this->saveactivity($activity);
        return response()->json($catgories);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $category = $this->checkrole();
        if (isset($category) && $category == '1' || Auth::user()->type == 'admin' || Auth::user()->type == 'dropshipper') {
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
            $toptool = Toptool::where('name', 'Category')->where('uid', $user)->where('store_id', $store_id)->first();
            if (isset($toptool)) {
                $toptool->count = $toptool->count + 1;
                $toptool->save();
            } else {
                $toptool = new Toptool();
                $toptool->name = "Category";
                $toptool->image = "categories.png";
                $toptool->url = "/category";
                $toptool->count = "1";
                $toptool->uid = $user;
                $toptool->store_id = $store_id;
                $toptool->customer_id = $customer_id;
                $toptool->creator = $user;
                $toptool->editor = $user;
                $toptool->save();
            }
            $activity = "Access Create Category Page";
            $this->saveactivity($activity);
            return view('admin.category.create')->with('urls', $urls);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \WebPConvert\Convert\Exceptions\ConversionFailedException
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
            return redirect()->back()->withErrors($validator);
        } else {
            $user = Auth::user()->id;
            $user_type = Auth::user()->type;
            if ($user_type == "admin" || $user_type == "dropshipper") {
                $customer = Customer::where('uid', $user)->first();
            }
            $store = Store::where('id', $customer->active_store)->first();

            $plan = Plan::find($store->plan_id);
            $category = Category::where('store_id', $store->id)->where('parent', 0)->count();

            if ($plan->category <= $category) {
                return back()->with('warning', 'Your Category add Limit up');
            }

            $category = new Category;
            $category->name = $request->name;
            $category->parent = "0";
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
            $activity = "Save Category " . $category->name;
            $this->saveactivity($activity);
            Session::flash('message', 'Category Save Successfully !');
            return redirect('category');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function categoryexport(Request $request)
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
        $fileName = 'category(' . $date . ').csv';
        $category = Category::where('store_id', $store_id)->where('parent', 0)->get();

        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );

        $columns = array('Name', 'Banner', 'Icon', 'Created_at');

        $callback = function () use ($category, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($category as $cat) {
                $row['Name'] = $cat->name;
                $row['Banner'] = $cat->banner;
                $row['Icon'] = $cat->icon;
                $row['Create Date'] = $cat->created_at;

                fputcsv($file, array($row['Name'], $row['Banner'], $row['Icon'], $row['Create Date']));
            }

            fclose($file);
        };
        $activity = "Export Category as csv";
        $this->saveactivity($activity);
        return response()->stream($callback, 200, $headers);
    }

    public function subcategoryexport(Request $request)
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
        $fileName = 'subcategory(' . $date . ').csv';
        $category = Category::where('store_id', $store_id)->where('parent', '!=', 0)->get();

        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );

        $columns = array('Name', 'Banner', 'Icon', 'Created_at');

        $callback = function () use ($category, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($category as $cat) {
                $row['Name'] = $cat->name;
                $row['Banner'] = $cat->banner;
                $row['Icon'] = $cat->icon;
                $row['Create Date'] = $cat->created_at;

                fputcsv($file, array($row['Name'], $row['Banner'], $row['Icon'], $row['Create Date']));
            }

            fclose($file);
        };
        $activity = " Subcategory Export as csv";
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
        // dd('sdfafa');
        $category = 1;
        if (isset($category) && $category == '1' || Auth::user()->type == 'admin' || Auth::user()->type == 'dropshipper') {
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
            $toptool = Toptool::where('name', 'Category')->where('uid', $user)->where('store_id', $store_id)->first();
            if (isset($toptool)) {
                $toptool->count = $toptool->count + 1;
                $toptool->save();
            } else {
                $toptool = new Toptool();
                $toptool->name = "Category";
                $toptool->image = "categories.png";
                $toptool->url = "/category";
                $toptool->count = "1";
                $toptool->uid = $user;
                $toptool->store_id = $store_id;
                $toptool->customer_id = $customer_id;
                $toptool->creator = $user;
                $toptool->editor = $user;
                $toptool->save();
            }

            $category = Category::find($id);
            // $category= DB::table('categories')->where('store_id', $store_id)->where('id', $id)->first();
            // $category= null;
            if (empty($category)) {
                return back();
            }
            $activity = " Access edit category Page " . $category->name;
            // $this->saveactivity($activity);
            // return $category;
            return view('admin.category.edit')->with('category', $category)->with('urls', $urls);
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
            $category->parent = "0";
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
            $activity = " Update Category " . $category->name;
            $this->saveactivity($activity);
            Session::flash('message', 'Category Update Successfully !');
            return redirect('category');
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
        } elseif ($user_type == 'staff') {
            $staff = Staff::where('uid', Auth::user()->id)->first();
            $store_id = $staff->store_id;
        }
        $category = Category::where('store_id', $store_id)->where('id', $id)->first();
        if (empty($category)) {
            return back();
        }
        $category->delete();

        $activity = " Delete Category " . $category->name;
        $this->saveactivity($activity);
        Session::flash('success_message', 'Category Delete Successfully !');
        return redirect('category');
    }

    public function deleteImage($id)
    {
        $userData = getUserData();
        $store_id = $userData['store_id'];

        $category = Category::where('store_id', $store_id)->where('id', $id)->first();
        if (!isset($category)) {
            return sendError("Category not found");
        }
        $category->banner = null;
        $category->update();

        $activity = " Delete Category Banner Successfully";
        $this->saveactivity($activity);
        return sendResponse("Category Banner Deleteed Successfully");
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

        $category = $this->checkrole();
        if (isset($category) && $category == '1' || Auth::user()->type == 'admin' || Auth::user()->type == 'dropshipper') {
            $category = Category::where('store_id', $store_id)->where('id', $id)->first();
            if (empty($category)) {
                return back();
            }
            $category->delete();
            Session::flash('success_message', 'Category Delete Successfully !');
            return redirect('category');
        }
    }


    /**
     *
     * Category status change like active or deactive and delete function
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|void
     */
    public function changecategorystatus(Request $request)
    {
        if ($request->text2 == '') {
            Session::flash('message', 'Please Select at least one item');
            return back();
        }
        if ($request->action == 'select') {
            Session::flash('message', 'Please Select a Option');
            return back();
        }

        // If active is active then status change to active
        if ($request->action == 'active') {
            $id = explode(',', $request->text2);
            if (isset($id) && count($id) > 0) {
                foreach ($id as $ids) {
                    $product = Category::find($ids);
                    $product->status = 'active';
                    $product->save();
                }
            }
            $activity = " Change Category Status";
            $this->saveactivity($activity);
            Session::flash('message', 'Successfully Activated');
            return back();
        }

        // If deactive is deactive then status change to deactive
        if ($request->action == 'deactive') {
            $id = explode(',', $request->text2);
            if (isset($id) && count($id) > 0) {
                foreach ($id as $ids) {
                    $product = Category::find($ids);
                    $product->status = 'deactive';
                    $product->save();
                }
            }
            $activity = " Change Category Status";
            $this->saveactivity($activity);
            Session::flash('message', 'Successfully Deactivate');
            return back();
        }

        // If delete is delete then status change to delete
        if ($request->action == 'delete') {
            $id = explode(',', $request->text2);
            if (isset($id) && count($id) > 0) {

                foreach ($id as $ids) {
                    Category::where('id', $ids)->delete();
                    Category::where('parent', $id)->delete();
                }
            }
            $activity = " Delete Category";
            $this->saveactivity($activity);
            Session::flash('message', 'Successfully Deleted');
            return back();
        }

    }
}
