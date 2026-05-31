<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Currency;
use App\Models\Design;
use App\Models\Headersetting;
use App\Models\Product;
use App\Models\WebsiteSetupDetails;
use App\Models\WebsiteSetupImage;
use App\Models\WebsiteSetupProducts;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Staff;
use App\Models\Customer;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Validator;
use Auth;
use App\Models\Role;
use App\Models\Toptool;
use App\Models\Plan;
use App\Models\Store;
use Carbon\Carbon;
use App\Models\Activitylog;
use App\Http\Traits\ActivityLogTraits;
use App\Models\Superstaff;
use App\Models\Websitesetup;
use Illuminate\Support\Facades\Session;

class StaffController extends Controller
{
    use ActivityLogTraits;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $stfs = $this->checkrole();
        if (isset($stfs) && $stfs == "1" || Auth::user()->type == 'admin' || Auth::user()->type == 'dropshipper') {
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
            $toptool = Toptool::where('name', 'Employee')->where('uid', $user)->where('store_id', $store_id)->first();
            if (isset($toptool)) {
                $toptool->count = $toptool->count + 1;
                $toptool->save();
            } else {
                $toptool = new Toptool();
                $toptool->name = "Employee";
                $toptool->image = "employee.png";
                $toptool->url = "/staff";
                $toptool->count = "1";
                $toptool->uid = $user;
                $toptool->store_id = $store_id;
                $toptool->customer_id = $customer_id;
                $toptool->creator = $user;
                $toptool->editor = $user;
                $toptool->save();
            }
            $list = User::join('staff', 'users.id', '=', 'staff.uid')->where('staff.store_id', $store_id)
                ->get(['users.*']);
            $activity = " Access Staff List Page ";
            $this->saveactivity($activity);
            $store = Store::find($store_id);
            $plan = Plan::find($store->plan_id);
            $limit = $plan->staff;
            // $list=User::where('type','staff')->paginate(50);
            return view('admin.staff.index')
                ->with('data', $list)->with('urls', $urls)->with('limit', $limit);
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
                        return $staff;
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

    public function create()
    {
        $stfs = $this->checkrole();
        if (isset($stfs) && $stfs == "1" || Auth::user()->type == 'admin' || Auth::user()->type == 'dropshipper') {
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
            $toptool = Toptool::where('name', 'Employee')->where('uid', $user)->where('store_id', $store_id)->first();
            if (isset($toptool)) {
                $toptool->count = $toptool->count + 1;
                $toptool->save();
            } else {
                $toptool = new Toptool();
                $toptool->name = "Employee";
                $toptool->image = "employee.png";
                $toptool->url = "/staff";
                $toptool->count = "1";
                $toptool->uid = $user;
                $toptool->store_id = $store_id;
                $toptool->customer_id = $customer_id;
                $toptool->creator = $user;
                $toptool->editor = $user;
                $toptool->save();
            }
            $activity = " Access Staff Create Page ";
            $this->saveactivity($activity);
            return view('admin.staff.create')->with('urls', $urls)->with('store_id', $store_id);
        }
    }

    public function staffexport(Request $request)
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
        $fileName = 'staff(' . $date . ').csv';
        $coupon = Staff::where('store_id', $store_id)->get();

        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );

        $columns = array('Name', 'Phone', 'Email', 'Address', 'Created_at');

        $callback = function () use ($coupon, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($coupon as $cat) {
                $row['Name'] = $cat->name;
                $row['Phone'] = $cat->phone;
                $row['Email'] = $cat->email;
                $row['Address'] = $cat->address;
                $row['Create Date'] = $cat->created_at;

                fputcsv($file, array($row['Name'], $row['Phone'], $row['Email'], $row['Address'], $row['Create Date']));
            }

            fclose($file);
        };
        $activity = " Export Staff List ";
        $this->saveactivity($activity);
        return response()->stream($callback, 200, $headers);
    }

    public function store(Request $request)
    {
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
        $store = Store::find($store_id);
        $plan = Plan::find($store->plan_id);
        $limit = $plan->staff;
        $staff = Staff::where('store_id', $store_id)->count();
        if ($staff >= $limit) {
            Session::flash('error', 'Staff Limit Reached');
            return back();
        }
        $rules = array(
            'name' => 'required',
            'username' => 'required|unique:staff',
            'password' => 'required'
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            Session::flash('error', 'Something incorrect');
            return redirect()->back()
                ->withErrors($validator);
        } else {

            $staff = new User();
            $staff->name = $request->name;
            $staff->username = $request->username;
            $staff->password = Hash::make($request->password);
            $staff->type = 'staff';
            $staff->role_id = $request->roleid;
            $staff->otp = "NULL";
            $staff->store_id = $store_id;
            $staff->customer_id = $customer_id;
            $staff->save();

            $notificationData = [
                "title" => "New staff register (" . ($staff->name ?? $staff->phone ?? '') . ") - " . formatDateWithTime($staff->created_at),
                "type" => "user_create",
                "user_type" => "admin",
                "store_id" => $staff->store_id,
            ];

            if (isset($notificationData['title']) && !empty($notificationData['title'])) {
                createNotification($notificationData);
            }

            $stf = new Staff();
            $stf->name = $request->name;
            $stf->phone = $request->phone;
            $stf->email = $request->email;
            $stf->username = $request->username;
            $stf->password = Hash::make($request->password);


            $stf->uid = $staff->id;
            $stf->customer_id = $customer_id;
            $stf->store_id = $store_id;
            $stf->creator = $user;
            $stf->editor = $user;
            $stf->status = "active";
            $stf->role_id = $request->roleid;
            if (isset($request->pos)) {
                $pos = implode(',', $request->pos);
                $stf->pos = $pos;
            }
            $stf->save();
            $activity = " Save Staff " . $stf->id;
            $this->saveactivity($activity);
            Session::flash('message', 'Successfully created!');
            return redirect()->route('admin.staff');
        }
    }

    public function edit($id)
    {
        $stfs = $this->checkrole();
        if (isset($stfs) && $stfs == "1" || Auth::user()->type == 'admin' || Auth::user()->type == 'dropshipper') {
            $urls = "staff";
            $singleData = User::find($id);
            $staff = Staff::where('username', $singleData->username)->first();
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
            $toptool = Toptool::where('name', 'Employee')->where('uid', $user)->where('store_id', $store_id)->first();
            if (isset($toptool)) {
                $toptool->count = $toptool->count + 1;
                $toptool->save();
            } else {
                $toptool = new Toptool();
                $toptool->name = "Employee";
                $toptool->image = "employee.png";
                $toptool->url = "/staff";
                $toptool->count = "1";
                $toptool->uid = $user;
                $toptool->store_id = $store_id;
                $toptool->customer_id = $customer_id;
                $toptool->creator = $user;
                $toptool->editor = $user;
                $toptool->save();
            }
            $activity = " Edit Staff " . $staff->id;
            $this->saveactivity($activity);
            return view('admin.staff.edit')
                ->with('singleData', $singleData)->with('staff', $staff)->with('urls', $urls)->with('store_id', $store_id);
        }
    }

    public function destroy($id)
    {
        $stfs = $this->checkrole();
        if (isset($stfs) && $stfs == "1" || Auth::user()->type == 'admin' || Auth::user()->type == 'dropshipper') {
            $user = User::find($id);
            $staff = Staff::where('uid', $user->id)->first();
            $staff->delete();
            User::find($id)->delete();
            $activity = " Delete Staff " . $id;
            $this->saveactivity($activity);
            Session::flash('success_message', 'Successfully Deleted!');
            return redirect('staff');
        }
    }

    public function changestaffssstatus(Request $request)
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
                    $product = Staff::find($ids);
                    $product->delete();
                }
            }
            $activity = " Delete Staff ";
            $this->saveactivity($activity);
            Session::flash('message', 'Successfully Deleted Staff');
            return back();
        }
    }

    public function webSetUp()
    {
        $urls = "wsetup";
        $data = Websitesetup::with("store")->whereNotIn('status', ['Complete'])->orderBy('id', 'DESC')->paginate(20);

        return view('superadmin.clientWebSetup.websitesetup')->with('urls', $urls)->with('data', $data);
    }

    public function webSetUpLogin(Request $request)
    {

        $store = Store::where('id', $request->store_id)->where('access_key', $request->access_key)->first();

        if (!empty($store)) {
            $user = User::find($store->user_id);
            if ($user->id > 100) {
                Auth::login($user);
            }
            if (Auth::check()) {
                return redirect()->route('admin.index');
            } else {
                return redirect('/login');
            }
        } else {
            Session::flash('error', 'Access Key Invalid');
        }

        return back();
    }

    public function workAssign()
    {
        $urls = "wsetup";
        $data['staff'] = Superstaff::where('role_id', 8)->get();
        $data['setup'] = Websitesetup::where('status', 'Pending')->where('editor', null)->orderBy('id', 'DESC')->get();
        return view('superadmin.clientWebSetup.workAssign', $data);
    }

    public function workAssignStore(Request $request)
    {
        // dd($request->all());
        $urls = "wsetup";
        $data['staff'] = Superstaff::where('role_id', 8)->get();
        $setup = Websitesetup::whereIn('id', $request->store)->orderBy('id', 'DESC')->get();

        foreach ($setup as $key => $value) {
            $value->editor = $request->staff;
            $value->update();
        }

        Session::flash('message', 'Work Assign Successfully');
        return back();
    }

    public function viewSetupDate($id)
    {
        if (is_null($id) || empty($id)) {
            Session::flash('error', 'Invalid ID');
            return back();
        }

        $websiteSetupData = WebsiteSetupDetails::where("store_id", $id)->latest()->first();
        if (!isset($websiteSetupData)) {
            Session::flash('error', 'Record not found!');
            return back();
        }

        $products = WebsiteSetupProducts::where("store_id", $id)->get();
        $productView = view("admin.addon.websitesetup-product-list", ['products' => $products, "staff" => true]);

        return view('superadmin.clientWebSetup.websitesetup-details', [
            "websiteSetupData" => $websiteSetupData,
            "productView" => $productView,
            "store_id" => $id,
        ]);
    }

    public function update(Request $request, $id)
    {
        // dd($request->all());
        $rules = array(
            'name' => 'required',
            'phone' => 'required',
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            Session::flash('error', 'Something Incorrect');
            return redirect()->back()
                ->withErrors($validator);
        } else {
            $staff = User::find($id);
            $staff->name = $request->name;
            $staff->username = $request->username;
            $staff->password = Hash::make($request->password);
            $staff->type = 'staff';
            $staff->role_id = $request->roleid;
            $staff->save();

            $stf = Staff::where('uid', $staff->id)->first();
            $stf->name = $request->name;
            $stf->phone = $request->phone;
            $stf->email = $request->email;
            $stf->username = $request->username;
            $stf->password = Hash::make($request->password);
            $stf->editor = $staff->id;
            $stf->status = "active";
            $stf->role_id = $request->roleid;
            if (isset($request->pos) && count($request->pos) > 0) {
                $pos = implode(',', $request->pos);
                $stf->pos = $pos;
            } else {
                $stf->pos = null;
            }
            $stf->save();
            $activity = " Update Staff " . $stf->id;
            $this->saveactivity($activity);
            Session::flash('message', 'Successfully Updates!');
            return redirect()->route('admin.staff');
        }
    }

    public function staffWebsitesetupSaveSetupDetails(Request $request)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'mobile_number' => 'required|string',
            'whats_app_number' => 'required|string',
            'email' => 'required|string|email',
            'delivery_cost' => 'required|numeric',
            'theme_color' => 'required|string',
        ], [
            'mobile_number.required' => 'Mobile Number is required!',
            'whats_app_number.required' => 'Whats App Number is required!',
            'email.required' => 'Email Address is required!',
            'email.email' => 'Please Enter Valid Email Address!',
            'delivery_cost.required' => 'Delivery Cost is required!',
            'delivery_cost.numeric' => 'Delivery Cost must be numeric!',
            'theme_color.required' => 'Theme color is required!',
        ]);


        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $store_id = $request->store_id ?? "";
        $store = Store::where("id", $store_id)->first();
        $id = $request->id ?? "";

        $details = WebsiteSetupDetails::where("id", $id)->where("store_id", $store_id)->first();
        if (!isset($details)) {
            return redirect()->back()->with("error", "Record not found!");
        }

        $details->facebook_link = $request->facebook_link ?? "";
        $details->instagram_link = $request->instagram_link ?? "";
        $details->mobile_number = $request->mobile_number ?? "";
        $details->whats_app_number = $request->whats_app_number ?? "";
        $details->youtube_link = $request->youtube_link ?? "";
        $details->email = $request->email ?? "";
        $details->delivery_cost = $request->delivery_cost ?? "";
        $details->shipping_area = $request->shipping_area ?? "";
        $details->tax = $request->tax ?? "";
        $details->address = $request->address ?? "";
        $details->theme_color = $request->theme_color ?? "";
        $details->short_description = $request->short_description ?? "";

        if ($request->file('logo')) {
            $image = $request->file('logo');
            $validation = imageValidation($image, $store_id);
            if ($validation) {
                return back()->with('error', $validation);
            }

            $imageUploadPath = 'assets/images/setting/';
            $imageName = updateFile($image, $imageUploadPath, $details->logo);

            $details->logo = $imageName;
        }

        $details->save();


        $hs = Headersetting::where("store_id", $store_id)->first();
        if (!isset($hs)) {
            $hs = new Headersetting();
            $hs->uid = $store->user_id;
            $hs->store_id = $store_id;
            $hs->customer_id = $store->customer_id;
            $hs->creator = $store->user_id;
            $hs->editor = $store->user_id;
        }

        if (!empty($details->short_description)) {
            $hs->short_description = $details->short_description;
        }
        if (!empty($details->logo)) {
            $hs->logo = $details->logo;
            $hs->favicon = $details->logo;
        }
        if (!empty($details->mobile_number)) {
            $hs->phone = $details->mobile_number;
        }
        if (!empty($details->address)) {
            $hs->address = $details->address;
        }
        if (!empty($details->email)) {
            $hs->email = $details->email;
        }
        if (!empty($details->facebook_link)) {
            $hs->facebook_link = $details->facebook_link;
        }
        if (!empty($details->instagram_link)) {
            $hs->instagram_link = $details->instagram_link;
        }
        if (!empty($details->youtube_link)) {
            $hs->youtube_link = $details->youtube_link;
        }
        if (!empty($details->whats_app_number)) {
            $hs->whatsapp_phone = $details->whats_app_number;
        }
        if (!empty($details->lined_in_link)) {
            $hs->lined_in_link = $details->lined_in_link;
        }
        if (!empty($details->tax)) {
            $hs->tax = $details->tax;
        }
        if (!empty($details->shipping_area)) {
            $hs->shipping_area_1 = $details->shipping_area;
        }
        if (!empty($details->delivery_cost)) {
            $hs->shipping_area_1_cost = $details->delivery_cost;
        }
        $hs->save();

        if (!empty($details->theme_color)) {
            $design = Design::where('store_id', $store_id)->first();
            if (!isset($design)) {
                $design = new Design;
                $design->uid = $store->user_id;
                $design->customer_id = $store->customer_id;
                $design->store_id = $store_id;
                $design->creator = $store->user_id;
                $design->editor = $store->user_id;
            }


            $design->header_color = $details->theme_color;
            $design->save();
        }

        $details->update_setting = 1;
        $details->update();

        return redirect()->back()->with("success", "Record Saved Successfully");
    }


    public function staffWebsitesetupSaveProduct(Request $request)
    {
        $store_id = $request->store_id ?? "";

        if (empty($request->product_name)) {
            return sendError("Product Name is required!");
        } elseif (empty($request->description)) {
            return sendError("Product Description is required!");
        } elseif (empty($request->category)) {
            return sendError("Product Category is required!");
        } elseif (empty($request->price)) {
            return sendError("Product Price is required!");
        } elseif (empty($store_id)) {
            return sendError("Store ID required!");
        }

        if (isset($store_id) && $store_id != "") {
            $product = new WebsiteSetupProducts();
            $product->store_id = $store_id;
            $product->model_no = $request->model_no;
            $product->product_name = $request->product_name;
            $product->description = $request->description;
            $product->category = $request->category;
            $product->sub_category = $request->sub_category;
            $product->price = $request->price;
            $product->brand = $request->brand;
            $product->supplier = $request->supplier;
            $product->cost = $request->cost;
            $product->discount = $request->discount;
            $product->color = $request->color;
            $product->size = $request->size;
            $product->unit = $request->unit;
            $product->save();

            $products = WebsiteSetupProducts::where("store_id", $store_id)->get();
            $view = view("admin.addon.websitesetup-product-list", ['products' => $products, 'staff' => true])->render();

            return sendResponse("Record saved successfully", $view);
        }

        return sendError("Record did not saved!");
    }


    public function staffWebsitesetupDeleteProduct($id)
    {
        $product = WebsiteSetupProducts::where("id", $id)->first();

        if (isset($product)) {
            $product->delete();

            return redirect()->back()->with("success", "Record deleted successfully");
        }

        return redirect()->back()->with("error", "Record not found!");

    }


    public function staffWebsitesetupViewProduct($id)
    {
        $product = WebsiteSetupProducts::where("id", $id)->first();

        if (isset($product)) {
            $store_id = $product->store_id ?? "";
            $moduleIsNull = ModulusStatus($store_id, 107);

            $activity = " Access Create Product Page";
            $this->saveactivity($activity);

            $store = Store::with('current_currency')->find($store_id);
            $current_currency = $store->current_currency;

            $currency = Currency::join('stores', 'stores.currency', 'currencies.id')->where('stores.id', $store_id)->first('code');
            $images = WebsiteSetupImage::where('store_id', $store_id)->where('product_id', $id)->get();

            return view("superadmin.clientWebSetup.websitesetup-product-view", [
                'product' => $product,
                'currency' => $currency,
                'store' => $store,
                'current_currency' => $current_currency,
                'store_id' => $store_id,
                'moduleIsNull' => $moduleIsNull,
                'images' => $images,
            ]);
        }

        return redirect()->back()->with("error", "Record not found!");

    }


    public function staffWebsitesetupUpdateProduct(Request $request)
    {
        $store_id = $request->store_id ?? "";

        if (empty($request->product_name)) {
            Session::flash('error', 'Product Name is required!');
            return back();
        } elseif (empty($request->description)) {
            Session::flash('error', 'Product Description is required!');
            return back();
        } elseif (empty($request->category)) {
            Session::flash('error', 'Product Category is required!');
            return back();
        } elseif (empty($request->price)) {
            Session::flash('error', 'Product Price is required!');
            return back();
        } elseif (empty($request->quantity)) {
            Session::flash('error', 'Product Quantity is required!');
            return back();
        } elseif (empty($store_id)) {
            Session::flash('error', 'Store ID required!');
            return back();
        }

        $product = WebsiteSetupProducts::where("id", $request->id ?? "")->where("store_id", $store_id)->first();

        if (isset($product)) {
            $product->model_no = $request->model_no;
            $product->product_name = $request->product_name;
            $product->description = $request->description;
            $product->category = $request->category;
            $product->sub_category = $request->sub_category;
            $product->price = $request->price;
            $product->brand = $request->brand;
            $product->supplier = $request->supplier;
            $product->cost = $request->cost;
            $product->quantity = $request->quantity;
            $product->discount = $request->discount;
            $product->discount_type = $request->discount_type;
            $product->save();

            Session::flash('success', 'Record saved successfully');
            return back();
        }

        Session::flash('error', 'Record did not saved!');
        return back();
    }


    public function runProductCreate($store)
    {
        $store_id = $store ?? "";

        if (empty($store_id)) {
            Session::flash('error', 'Store ID required!');
            return back();
        }

        $store = Store::find($store_id);
        if (!isset($store)) {
            Session::flash('error', 'Store not found!');
            return back();
        }

        $products = WebsiteSetupProducts::where("store_id", $store_id)->get();

        if (isset($products) && count($products) > 0) {
            foreach ($products as $product) {
                if (empty($product->product_name) || empty($product->description) || empty($product->category) || empty($product->price) || empty($product->quantity)) {
                    Session::flash('error', 'Product Title/Description/Category/Price/Quantity is required!');
                    return back();
                }

                $categoryIds = [];

                if (isset($product->category) && !empty($product->category)) {
                    $categories = explode(',', $product->category);

                    if (isset($categories) && count($categories) > 0) {
                        foreach ($categories as $categoryName) {
                            $category = new Category;
                            $category->name = $categoryName;
                            $category->icon = "1655896921.png";
                            $category->parent = "0";
                            $category->status = 'active';
                            $category->position = 1;
                            $category->save();
                            $categoryIds[] = $category->id;
                        }
                    }
                }

                if (count($categoryIds) > 0) {
                    $categoryIds = implode(',', $categoryIds);
                }

                $productModel = new Product;
                $productModel->name = $product->product_name;
                $productModel->description = $product->description;
                $productModel->regular_price = $product->price;
                $productModel->discount_type = $product->discount_type;
                $productModel->prev_discount = $product->discount_type;
                if ($product->discount_type != "no_discount") {
                    $productModel->discount_product = 1;
                }
                $productModel->promotional_price = $product->discount ?? 0.00;
                $productModel->quantity = $product->quantity;
                $productModel->cost = $product->cost;
                $productModel->currency_id = $store->currency ?? 1;
                $productModel->category = $categoryIds ?? "";
                $productModel->status = "active";
                $productModel->SKU = $productModel->model_no ?? "";
                $productModel->store_id = $store_id;
                $productModel->save();

                $product->save_status = 1;
                $product->update();
            }
        }

        Session::flash('success', 'Product created successfully!');
        return back();
    }

    public function uploadStatusComplete($store)
    {
        try {
            $store_id = $store ?? "";

            if (empty($store_id)) {
                Session::flash('error', 'Store ID required!');
                return back();
            }

            $store = Store::find($store_id);
            if (!isset($store)) {
                Session::flash('error', 'Store not found!');
                return back();
            }

            $this->deleteWebsiteSetupData($store_id);

            $websiteSetup = Websitesetup::where("store_id", $store_id)->first();
            if (isset($websiteSetup)) {
                $websiteSetup->status = "Complete";
                $websiteSetup->save();
            }

            Session::flash('success', 'Setup completed successfully!');
            return redirect()->route("staff.webSetUp");
        } catch (\Exception) {
            Session::flash('error', 'Something went wrong!');
            return back();
        }

    }


    public function deleteWebsiteSetupData($store_id)
    {
        try {
            $products = WebsiteSetupProducts::where("store_id", $store_id)->get();
            if (isset($products) && count($products) > 0) {
                foreach ($products as $product) {
                    $productImages = WebsiteSetupImage::where("product_id", $product->id)->where("store_id", $store_id)->get();
                    foreach ($productImages as $item) {
                        if (isset($item->image)) {
                            $imageUploadPath = public_path('assets/images/setup') . "/" . $item->image;

                            if (file_exists($imageUploadPath)) {
                                unlink($imageUploadPath);
                            }
                        }
                        $item->delete();
                    }
                    $product->delete();
                }
            }
        } catch (\Exception) {

        }

    }


}
