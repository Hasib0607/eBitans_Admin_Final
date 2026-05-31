<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\Store;
use App\Models\Staff;
use App\Models\Role;
use App\Models\Customer;
use App\Models\Toptool;
use App\Models\Product;
use App\Http\Traits\ActivityLogTraits;
use App\Models\Activitylog;
use App\Models\Superrole;
use App\Models\Superstaff;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    use ActivityLogTraits;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $urls = "catagories";
        $catagories = Category::select('categories.*')
            ->withCount('products')
            ->whereNull('categories.store_id')
            ->whereNull('categories.customer_id')
            ->groupBy('categories.id', 'categories.name')
            ->get();
        return view('superadmin.store_manage.category.index', compact('catagories', 'urls'));
    }

    public function updateposition(Request $request)
    {
        $value = $request->value;
        $id = $request->id;
        $test = Category::where('id', $id)->first();
        $test->position = $value;
        $test->save();

        return response()->json($test);
    }

    public function changecatstatus(Request $request)
    {
        $user = Auth::user()->id;
        $user_type = Auth::user()->type;

        if ($user_type == "superadmin") {
            $customer = Customer::where('uid', $user)->first();
        } elseif ($user_type == 'staff') {
            $staff = Staff::where('uid', Auth::user()->id)->first();
        }

        $catgories = Category::where('id', $request->id)->first();
        if (isset($catgories) && $catgories->status == 'active') {
            $catgories->status = 'inactive';
            $status = 'Category inactive Successfully !';
        } else {
            $catgories->status = 'active';
            $status = 'Category active Successfully !';
        }
        $catgories->save();

        $data = $catgories;
        $activity = "Change Category Status " . $catgories->name;
        $this->saveactivity($activity);

        return response()->json(['data' => $data, 'status' => $status]);
    }

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

    public function store(Request $request)
    {
        $rules = array(
            'name' => 'required',
            'icon' => 'required',
            'banner' => 'required|max:2048',
            'position' => 'required'
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        } else {
            $user = Auth::user()->id;
            $user_type = Auth::user()->type;

            $category = new Category;
            $category->name = $request->name;
            $category->parent = "0";
            if (isset($request->icon) && $request->icon != 'null') {
                $category->icon = $request->icon;
            }

            if ($request->banner) {
                $imageName = "b" . Carbon::now()->timestamp . '.' . $request->banner->extension();
                $request->banner->storeAs('category', $imageName);
                $category->banner = $imageName;
            }

            if ($request->status == 'on') {
                $category->status = 'active';
            } else {
                $category->status = 'inactive';
            }

            $category->position = $request->position;

            $category->save();


            $activity = "Save Category " . $category->name;
            $this->saveactivity($activity);

            Session::flash('message', 'Category Save Successfully !');
            return redirect()->back();
        }
    }

    public function catAdd($id)
    {
        if (Auth::user()->type == 'superadmin') {
            $urls = "superrolepermission";
            $catgories = Category::where('store_id', '!=', null)->where('customer_id', '!=', null)->where('status', '!=', 'RecycleBin')->orderBy('name', 'ASC')->get();
            $id = $id;

            return view('superadmin.store_manage.category.add_category', compact('catgories', 'urls', 'id'));
        }
    }

    public function catAddStore(Request $request)
    {
        if (Auth::user()->type == 'superadmin') {

            if ($request->categoryId) {
                foreach ($request->categoryId as $id) {
                    $catgories = Category::find($id);
                    $catgories->market_id = $request->market_cat_id;
                    $catgories->update();
                }
            }

            if ($request->DeAccategoryId) {
                foreach ($request->DeAccategoryId as $id) {
                    if ($id) {
                        $catgories = Category::find($id);
                        $catgories->market_id = 0;
                        $catgories->update();
                    }
                }
            }

            return redirect()->route('superadmin.store.category');
        }
    }

    public function update(Request $request, $id)
    {
        $rules = array(
            'name' => 'required',
            'position' => 'required',
            'banner' => 'max:2048'
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator);
        } else {
            $user = Auth::user()->id;
            $user_type = Auth::user()->type;
            if ($user_type == "superadmin") {
                $customer = Customer::where('uid', $user)->first();
            }
            $category = Category::find($id);
            $category->name = $request->name;
            $category->parent = "0";
            if (isset($request->icon) && $request->icon != 'null') {
                $category->icon = $request->icon;
            }
            if ($request->banner) {
                $imageName = "b" . Carbon::now()->timestamp . '.' . $request->banner->extension();
                $request->banner->storeAs('category', $imageName);
                $category->banner = $imageName;
            }
            if ($request->status == 'on') {
                $category->status = 'active';
            } else {
                $category->status = 'inactive';
            }
            $category->position = $request->position;
            $category->editor = $user;
            $category->save();

            Session::flash('message', 'Category Update Successfully !');
            return redirect()->back();
        }
    }

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

    public function edit($id)
    {
        if (Auth::user()->type == 'superadmin') {
            $urls = "superrolepermission";
            $category = Category::find($id);
            $id = $id;

            return view('superadmin.store_manage.category.edit', compact('category', 'urls', 'id'));
        }
    }

    public function deletecat($id)
    {
        $category = Category::find($id);
        $category->status = "RecycleBin";
        $category->save();
        $products = Product::where('category', $id)->get();
        if (isset($products) && count($products) > 0) {
            foreach ($products as $product) {
                $prod = Product::find($product->id);
                $prod->status = "RecycleBin";
                $prod->save();
            }
        }
        $subcat = Category::where('parent', $id)->get();
        if (isset($subcat) && count($subcat) > 0) {
            foreach ($subcat as $scat) {
                $categoryss = Category::find($scat->id);
                $categoryss->delete();
            }
        }
        $user = Auth::user()->id;
        $user_type = Auth::user()->type;
        if ($user_type == "superadmin") {
            $customer = Customer::where('uid', $user)->first();
        } elseif ($user_type == 'staff') {
            $staff = Staff::where('uid', Auth::user()->id)->first();
        }
        $activity = " Delete Category " . $category->name;
        $this->saveactivity($activity);

        Session::flash('success_message', 'Category Delete Successfully !');
        return redirect()->back();
    }

    public function destroy($id)
    {
        $category = $this->checkrole();
        if (isset($category) && $category == '1' || Auth::user()->type == 'admin' || Auth::user()->type == 'dropshipper') {
            $category = Category::find($id);
            $category->delete();

            Session::flash('success_message', 'Category Delete Successfully !');
            return redirect('category');
        }
    }

    public function changecategorystatus(Request $request)
    {
        if ($request->text2 == '') {
            Session::flash('message', 'Please Select at least one item');
            return redirect()->back();
        }
        if ($request->action == 'select') {
            Session::flash('message', 'Please Select a Option');
            return redirect()->back();
        }

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

            Session::flash('message', 'Successfully Active');
            return redirect()->back();
        }
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

            Session::flash('message', 'Successfully Deactive');
            return redirect()->back();
        }
        if ($request->action == 'delete') {
            $id = explode(',', $request->text2);
            if (isset($id) && count($id) > 0) {
                foreach ($id as $ids) {
                    $product = Category::find($ids);
                    $product->status = 'RecycleBin';
                    $product->save();
                }
            }
            $activity = " Delete Category";
            $this->saveactivity($activity);

            Session::flash('message', 'Successfully Deleted');
            return redirect()->back();
        }

    }
}
