<?php

namespace App\Http\Controllers\SuperAdmin;

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
use App\Models\Toptool;
use App\Models\Activitylog;
use App\Models\Work;
use App\Models\Store;
use App\Models\Posplan;
use Auth;
use PDF;
use App\Http\Traits\ActivityLogTraits;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    use ActivityLogTraits;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index1($subdomain)
    {
        if ($subdomain != "blog") {
            return redirect('/payment');
        } else {
            dd("ok");
        }
    }

    public function pro(Request $request)
    {
        Session::put('role', $request->id);
        $role = Role::where('id', Session::get('role'))->first();
        $permission = explode(',', $role->permission);
        Session::put('permission', $permission);
        $data = 1;

        return response()->json($data);
    }

    public function index()
    {
        $this->checkrole();
        $urls = "product";
        // dd(Auth::user());
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
            $customer = Customer::where('id', $staff->customer_id)->first();
        }
        $limit = 0;
        $store = Store::find($store_id);
        if ($store->plan_id != 'NULL') {
            $plan = Plan::find($store->plan_id);
            if ($store->expiry_date >= Carbon::now()) {
                if (isset($store->pos_plan_id)) {
                    if ($store->pos_plan_expiry_date >= Carbon::now()) {
                        $posplan = Posplan::find($store->pos_plan_id);
                        if ($plan->product > $posplan->product) {
                            $limit = $plan->product;
                        } else {
                            $limit = $posplan->product;
                        }
                    } else {
                        $limit = $plan->product;
                    }
                } else {
                    $limit = $plan->product;
                }
            } else {
                $limit = $limit;
            }
        } else {
            if (isset($store->pos_plan_id)) {
                if ($store->pos_plan_expiry_date >= Carbon::now()) {
                    $posplan = Posplan::find($store->pos_plan_id);
                    $limit = $posplan->product;
                } else {
                    $limit = $limit;
                }
            } else {
                $limit = $limit;
            }
        }
        if (isset($store->digital_plan_id)) {
            if ($store->digital_plan_end_date >= Carbon::now()) {
                $limit = 10000000;
            } else {
                $limit = $limit;
            }
        }
        // if($store->plan_id != 'NULL'){
        //     $plan=Plan::find($store->plan_id);
        //     $limit=$plan->product ?? null;
        //     $product=Product::where('store_id',$customer->active_store)->where('status','!=','RecycleBin')->orderBy('id','DESC')->take($plan->product)->latest()->get();
        // }else{
        //     $limit=$plan->product ?? null;
        //     $product=Product::where('store_id',$customer->active_store)->where('status','!=','RecycleBin')->orderBy('id','DESC')->latest()->get();
        // }
        $product = Product::where('store_id', $customer->active_store)->where('status', '!=',
            'RecycleBin')->orderBy('id', 'DESC')->take($limit)->latest()->get();
        $toptool = Toptool::where('name', 'Product')->where('uid', $user)->where('store_id', $store_id)->first();
        if (isset($toptool)) {
            $toptool->count = $toptool->count + 1;
            $toptool->save();
        } else {
            $toptool = new Toptool();
            $toptool->name = "Product";
            $toptool->image = "box.png";
            $toptool->url = "/products";
            $toptool->count = "1";
            $toptool->uid = $user;
            $toptool->store_id = $store_id;
            $toptool->customer_id = $customer_id;
            $toptool->creator = $user;
            $toptool->editor = $user;
            $toptool->save();
        }
        $activity = " Browse Product List";
        $this->saveactivity($activity);
        $store_id = $store_id;
        $productcount = Product::where('creator', Auth::user()->id)->where('status', '!=', 'RecycleBin')->get();
        return view('admin.product.index')->with('products', $product)->with('urls', $urls)->with('limit',
            $limit)->with('productcount', $productcount)->with('store_id', $store_id);
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
                        return $product;
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

        $user = Auth::user()->id;
        $user_type = Auth::user()->type;
        if ($user_type == "admin" || $user_type == "dropshipper") {
            $customer = Customer::where('uid', $user)->first();
            $store_id = $customer->active_store;
            $customer_id = $customer->id;
        } else {
            $staff = Staff::where('uid', Auth::user()->id)->first();
            $store_id = $staff->store_id;
            $customer_id = $staff->customer_id;
        }
        $store_id = $store_id;
        $store = Store::find($store_id);
        $limit = 0;
        if ($store->plan_id != 'NULL') {
            $plan = Plan::find($store->plan_id);
            if ($store->expiry_date >= Carbon::now()) {
                if (isset($store->pos_plan_id)) {
                    if ($store->pos_plan_expiry_date >= Carbon::now()) {
                        $posplan = Posplan::find($store->pos_plan_id);
                        if ($plan->product > $posplan->product) {
                            $limit = $plan->product;
                        } else {
                            $limit = $posplan->product;
                        }
                    } else {
                        $limit = $plan->product;
                    }
                } else {
                    $limit = $plan->product;
                }
            } else {
                $limit = $limit;
            }
        } else {
            if (isset($store->pos_plan_id)) {
                if ($store->pos_plan_expiry_date >= Carbon::now()) {
                    $posplan = Posplan::find($store->pos_plan_id);
                    $limit = $posplan->product;
                } else {
                    $limit = $limit;
                }
            } else {
                $limit = $limit;
            }
        }
        if (isset($store->digital_plan_id)) {
            if ($store->digital_plan_end_date >= Carbon::now()) {
                $limit = 10000000;
            } else {
                $limit = $limit;
            }
        }
        $allproduct = Product::where('store_id', $store_id)->where('status', '!=', 'RecycleBin')->count();
        if ($allproduct > $limit) {
            Session::flash('error', 'Product Add Limit Reacted');
            return back()->withInput();
        }
        $rules = array(
            'product_name' => 'required',
            'description' => 'required',
            'regular_price' => 'required',
            'discount_type' => 'required',
            'quantity' => 'required',
            'image' => 'required',
            'category' => 'required',
            'SKU' => 'required'
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withInput()
                ->withErrors($validator);
        } else {
            $produt = Product::where('SKU', $request->SKU)->where('store_id', $store_id)->first();
            $oc = 0;
            if ($request->att == 'onlycolor') {
                foreach ($request->quantitysss as $key => $qty) {
                    $oc = $oc + (int)$request->quantitysss[$key];
                }
            }
            if ($oc > $request->quantity) {
                Session::flash('error', 'Product variant quantity exited !');
                return back()->withInput();
            }
            // dd($request->all());
            $user = Auth::user()->id;
            $user_type = Auth::user()->type;
            if ($user_type == "admin" || $user_type == "dropshipper") {
                $customer = Customer::where('uid', $user)->first();
                $store_id = $customer->active_store;
                $customer_id = $customer->id;
            } else {
                $staff = Staff::where('uid', Auth::user()->id)->first();
                $store_id = $staff->store_id;
                $customer_id = $staff->customer_id;
            }
            $store_id = $store_id;
            $store = Store::find($store_id);
            $plan = Plan::find($store->plan_id);
            $produt = Product::where('SKU', $request->SKU)->where('store_id', $store_id)->first();
            if (isset($produt)) {
                Session::flash('error', 'SKU Already Taken !');
                return back()->withInput();
            }

            if ($request->category == "Select") {
                Session::flash('error', 'Category Must be Given !');
                return back()->withInput();
            }
            $qut = 0;
            if (isset($request->sid)) {
                foreach ($request->sid as $key => $sids) {
                    foreach ($sids as $keys => $size) {
                        $qut = $qut + (int)$request->quantitys[$key][$keys];
                    }
                }
            }
            if ($qut > $request->quantity) {
                Session::flash('error_message', 'Product variant quantity exited !');
                return back()->withInput();
            } else {
                $product = new Product;
                $product->name = $request->product_name;
                $product->description = $request->description;
                $product->regular_price = $request->regular_price;
                $product->discount_type = $request->discount_type;
                $product->promotional_price = $request->promotional_price;
                $product->tax_type = $request->tax_type;
                $product->tax_rate = $request->tax_rate;
                $product->quantity = $request->quantity;
                $product->seo_keywords = $request->seo;
                $product->weight = $request->weight;
                $product->shipping_fee = $request->shipping_fee;
                $product->brand = $request->brand;
                $product->supplier = $request->supplier;
                $product->cost = $request->cost;
                if ($request->barcode == "") {
                    $id = date('y') . rand(1, 10000);
                    $product->barcode = $id;
                } elseif ($request->barcode == null) {
                    $id = date('y') . rand(1, 10000);
                    $product->barcode = $id;
                } else {
                    $product->barcode = $request->barcode;
                }
                if (isset($request->image)) {
                    foreach ($request->image as $key => $image) {
                        $imgName = Carbon::now()->timestamp . $key . '.' . $image->extension();
                        $image->storeAs('product', $imgName);
                        $imagesname[] = $imgName;
                    }
                    $product->images = implode(',', $imagesname);
                }
                $product->category = $request->category;
                $product->currency_id = $store->currency;
                $product->subcategory = $request->subcategory;
                $product->tags = $request->tagss;
                $product->status = "active";
                $product->SKU = $request->SKU;
                if (isset($request->best_sell)) {
                    $product->best_sell = 1;
                } else {
                    $product->best_sell = 0;
                }
                if (isset($request->feature)) {
                    $product->feature = 1;
                } else {
                    $product->feature = 0;
                }
                $user = Auth::user()->id;
                $user_type = Auth::user()->type;

                $store_id = $store_id;
                $product->uid = $user;
                $product->customer_id = $customer_id;
                $product->store_id = $store_id;
                $product->creator = $user;
                $product->editor = $user;
                $product->save();
                if ($request->att == 'unit') {
                    //dd($request->unit);
                    foreach ($request->unit as $key => $units) {
                        //dd($request->unit[$key]);
                        $variant = new Veriant;
                        $variant->pid = $product->id;
                        $variant->unit = $request->unit[$key];
                        $variant->volume = $request->volume[$key];
                        $variant->quantity = $request->quantityssss[$key];
                        $variant->additional_price = $request->pricessss[$key];
                        $variant->save();
                    }
                } elseif ($request->att == 'size') {
                    foreach ($request->quantityss as $key => $qty) {
                        if ($request->quantityss[$key] != "") {
                            $variant = new Veriant;
                            $variant->pid = $product->id;
                            $variant->size = $request->sizess[$key];
                            $variant->quantity = $request->quantityss[$key];
                            $variant->additional_price = $request->pricess[$key];
                            $variant->save();
                        }
                    }
                }
                if ($request->att == 'onlycolor') {
                    foreach ($request->quantitysss as $key => $qty) {
                        $variant = new Veriant;
                        $variant->pid = $product->id;
                        $variant->color = $request->colors[$key];
                        $variant->quantity = $request->quantitysss[$key];
                        $variant->additional_price = $request->pricesss[$key];
                        $variant->save();
                    }
                }
                if (isset($request->sid)) {
                    foreach ($request->sid as $key => $sids) {
                        foreach ($sids as $keys => $size) {
                            $veriant = new Veriant;
                            if ($key != '0') {
                                $keyss = $key . '1';
                                $veriant->color = $request->color[$keyss][0];
                            } else {
                                $veriant->color = $request->color[$key][0];
                            }
                            $veriant->pid = $product->id;
                            $veriant->size = $request->size[$key][$keys];
                            $veriant->quantity = $request->quantitys[$key][$keys];
                            $veriant->additional_price = $request->price[$key][$keys];
                            $veriant->save();
                        }
                    }
                }
                $activity = " Save Product";
                $this->saveactivity($activity);
                Session::flash('message', 'Product Save Successfully !');
                return redirect()->route('admin.allproducts');
            }
        }
    }

    public function viewproduct($id)
    {
        $product = Product::find($id);
        $url1 = "inventory";
        return view('admin.product.view', compact('product', 'url1'));
    }

    public function printbarcode()
    {
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
            $customer = Customer::where('id', $staff->customer_id)->first();
        }
        $products = Product::where('store_id', $store_id)->where('barcode', '!=', null)->get();
        // dd($products);
        return view('admin.product.barcode', compact('urls', 'products'));
    }

    public function selectedBarcode(Request $request)
    {

        // dd($request->all());
        if ($request->barCodeId == '') {
            Session::flash('message', 'Please Select Product');
            return back();
        }


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
            $customer = Customer::where('id', $staff->customer_id)->first();
        }

        $ids = explode(',', $request->barCodeId);
        if (isset($ids) && count($ids) > 0) {
            foreach ($ids as $key => $id) {
                $products[$key] = Product::where('store_id', $store_id)->where('id', $id)->where('barcode', '!=',
                    null)->first();
            }
        }

        // $products=Product::where('store_id',$store_id)->where('barcode','!=',null)->get();
        // dd($products);
        // $data = [
        //     'title' => 'Welcome to ItSolutionStuff.com',
        //     'date' => date('m/d/Y')
        // ];

        $pdf = PDF::loadView('admin.product.viewbar', compact('urls', 'products'));

        return $pdf->stream();

        return view('admin.product.viewbar', compact('urls', 'products'));
        // return view('admin.product.barcode',compact('urls','products'));
    }

    public function updateattribute(Request $request)
    {
        $veriant = Veriant::find($request->id);
        $veriant->color = $request->color;
        $veriant->size = $request->size;
        $veriant->quantity = $request->quantity;
        $veriant->additional_price = $request->additional_price;
        $veriant->save();
        $data = $veriant->convertCurrency();
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
        $activity = " Update Attribute";
        $this->saveactivity($activity);
        return response()->json($data);
    }

    public function deleteattribute(Request $request)
    {
        $veriant = Veriant::find($request->id);
        $veriant->delete();
        $data = "Success";
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
        $activity = " Delete Attribute";
        $this->saveactivity($activity);
        return response()->json($data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        $product = $this->checkrole();
        if (isset($product) && $product == '1' || Auth::user()->type == 'admin' || Auth::user()->type == 'dropshipper') {
            $product = Product::find($id);
            $product->status = "RecycleBin";
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
            $activity = " Delete Product";
            $this->saveactivity($activity);
            $product->save();
            Session::flash('success_message', 'Product Delete Successfully !');
            return back();
        }
    }

    public function updatesizeattribute(Request $request)
    {
        $veriant = Veriant::find($request->id);
        $veriant->size = $request->size;
        $veriant->quantity = $request->quantity;
        $veriant->additional_price = $request->additional_price;
        $veriant->save();
        $data = $veriant->convertCurrency();
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
        $activity = " Update Size Attribute";
        $this->saveactivity($activity);
        return response()->json($data);
    }

    public function deletesizeattribute(Request $request)
    {
        $veriant = Veriant::find($request->id);
        $veriant->delete();
        $data = "Success";
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
        $activity = " Delete Size Attribute";
        $this->saveactivity($activity);
        return response()->json($data);
    }

    public function updateunitattribute(Request $request)
    {
        $veriant = Veriant::find($request->id);
        $veriant->unit = $request->unit;
        $veriant->volume = $request->volume;
        $veriant->quantity = $request->quantity;
        $veriant->additional_price = $request->additional_price;
        $veriant->save();
        $data = $veriant->convertCurrency();
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
        $activity = " Update Unit Attribute";
        $this->saveactivity($activity);
        return response()->json($data);
    }

    public function deleteunitattribute(Request $request)
    {
        $veriant = Veriant::find($request->id);
        $veriant->delete();
        $data = "Success";
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
        $activity = " Delete Unit Attribute";
        $this->saveactivity($activity);
        return response()->json($data);
    }

    public function updateonlycolorattribute(Request $request)
    {
        $veriant = Veriant::find($request->id);
        $veriant->color = $request->color;
        $veriant->quantity = $request->quantity;
        $veriant->additional_price = $request->additional_price;
        $veriant->save();
        $data = $veriant->convertCurrency();
        return response()->json($data);
    }

    public function deleteonlycolorattribute(Request $request)
    {
        $veriant = Veriant::find($request->id);
        $veriant->delete();
        $data = "Success";
        return response()->json($data);
    }

    public function allss()
    {
        return view('admin.product.new');
    }

    public function create()
    {
        $product = $this->checkrole();
        if (isset($product) && $product == '1' || Auth::user()->type == 'admin' || Auth::user()->type == 'dropshipper') {
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
            $toptool = Toptool::where('name', 'Product')->where('uid', $user)->where('store_id', $store_id)->first();
            if (isset($toptool)) {
                $toptool->count = $toptool->count + 1;
                $toptool->save();
            } else {
                $toptool = new Toptool();
                $toptool->name = "Product";
                $toptool->image = "box.png";
                $toptool->url = "/products";
                $toptool->count = "1";
                $toptool->uid = $user;
                $toptool->store_id = $store_id;
                $toptool->customer_id = $customer_id;
                $toptool->creator = $user;
                $toptool->editor = $user;
                $toptool->save();
            }
            $store_id = $store_id;
            $activity = " Access Create Product Page";
            $this->saveactivity($activity);
            return view('admin.product.create')->with('urls', $urls)->with('store_id', $store_id);
        } else {
            return redirect('/');
        }
    }

    public function getsubcat(Request $request)
    {
        $data = Category::where('parent', $request->catid)->where('status', 'active')->get();
        return response()->json($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function changeprostatus(Request $request)
    {
        $id = $request->id;
        $value = $request->value;
        $product = Product::find($id);
        if (isset($product) && $product->status == 'active') {
            $product->status = 'inactive';
        } else {
            $product->status = "active";
        }
        $product->save();
        $data = $product;
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
        $activity = " Change Product Status";
        $this->saveactivity($activity);
        return response()->json($data);
    }

    public function productdatefilter(Request $request)
    {
        $urls = "product";
        $user = Auth::user()->id;
        $user_type = Auth::user()->type;
        if ($user_type == "admin" || $user_type == "dropshipper") {
            $customer = Customer::where('uid', $user)->first();
            $store_id = $customer->active_store;
        } elseif ($user_type == 'staff') {
            $staff = Staff::where('uid', Auth::user()->id)->first();
            $store_id = $staff->store_id;
            $customer = Customer::where('id', $staff->customer_id)->first();
        }
        $store_id = $store_id;
        $store = Store::find($store_id);
        $plan = Plan::find($store->plan_id);
        $limit = $plan->product;
        $product = Product::where('store_id', $store_id)->where('status', '!=', 'RecycleBin')->orderBy('id',
            'DESC')->take($limit)->latest()->get();
        $from = $request->formdate;
        $to = $request->enddate;
        // dd($from);
        $activity = " Fiter Product";
        $this->saveactivity($activity);
        $product = Product::whereBetween('created_at', [$from, $to])->where('store_id', $store_id)->where('status',
            '!=', 'RecycleBin')->get();
        return view('admin.product.index')->with('products', $product)->with('urls', $urls)->with('limit',
            $limit)->with('productcount', $product)->with('store_id', $store_id)->with('from', $from)->with('to', $to);
    }


    public function exportCsv(Request $request)
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
        $fileName = 'products(' . $date . ').csv';
        $products = Product::where('store_id', $store_id)->where('status', '!=', 'RecycleBin')->get();

        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );

        $columns = array(
            'Name',
            'SKU',
            'Description',
            'Regular Price',
            'Discount Type',
            'Promotional Price',
            'Tax Type',
            'Tax Rate',
            'Quantity',
            'Weight',
            'Shipping Charge',
            'Category',
            'Subcategory',
            'Tags',
            'Create Date'
        );

        $callback = function () use ($products, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($products as $product) {
                $row['Name'] = $product->name;
                $row['SKU'] = $product->SKU;
                $row['Description'] = $product->description;
                $row['Regular Price'] = $product->regular_price;
                $row['Discount Type'] = $product->discount_type;
                $row['Promotional Price'] = $product->promotional_price;
                $row['Tax Type'] = $product->tax_type;
                $row['Tax Rate'] = $product->tax_rate;
                $row['Quantity'] = $product->quantity;
                $row['Weight'] = $product->weight;
                $row['Shipping Charge'] = $product->shipping_fee;
                $category = Category::find($product->category);
                $row['Category'] = $category->name;
                if (isset($product->subcategory)) {
                    $subcat = Category::find($product->subcategory);
                    $row['Subcategory'] = $subcat->name;
                } else {
                    $row['Subcategory'] = "";
                }
                $row['Tags'] = $product->tags;
                $row['Create Date'] = $product->created_at;

                fputcsv($file, array(
                    $row['Name'],
                    $row['SKU'],
                    $row['Description'],
                    $row['Regular Price'],
                    $row['Discount Type'],
                    $row['Promotional Price'],
                    $row['Tax Type'],
                    $row['Tax Rate'],
                    $row['Quantity'],
                    $row['Weight'],
                    $row['Shipping Charge'],
                    $row['Category'],
                    $row['Subcategory'],
                    $row['Tags'],
                    $row['Create Date']
                ));
            }

            fclose($file);
        };
        $activity = " Product Export CSV";
        $this->saveactivity($activity);
        return response()->stream($callback, 200, $headers);
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
        $product = $this->checkrole();
        if (isset($product) && $product == '1' || Auth::user()->type == 'admin' || Auth::user()->type == 'dropshipper') {
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
            $toptool = Toptool::where('name', 'Product')->where('uid', $user)->where('store_id', $store_id)->first();
            if (isset($toptool)) {
                $toptool->count = $toptool->count + 1;
                $toptool->save();
            } else {
                $toptool = new Toptool();
                $toptool->name = "Product";
                $toptool->image = "box.png";
                $toptool->url = "/products";
                $toptool->count = "1";
                $toptool->uid = $user;
                $toptool->store_id = $store_id;
                $toptool->customer_id = $customer_id;
                $toptool->creator = $user;
                $toptool->editor = $user;
                $toptool->save();
            }
            $store_id = $store_id;
            $activity = " Access Edit Product Page";
            $this->saveactivity($activity);
            $product = Product::find($id);
            return view('admin.product.edit')->with('product', $product)->with('urls', $urls)->with('store_id',
                $store_id);
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
        // dd($request->quantitys);
        if ($request->category == "Select") {
            Session::flash('error', 'Category Must be Given !');
            return back();
        }
        $rules = array(
            'product_name' => 'required',
            'description' => 'required',
            'regular_price' => 'required',
            'discount_type' => 'required',
            'quantity' => 'required',
            'category' => 'required'
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator);
        } else {
            $qut = 0;
            $qut1 = 0;
            if (isset($request->sid)) {
                foreach ($request->sid as $key => $sids) {
                    foreach ($sids as $keys => $size) {
                        $qut = $qut + (int)$request->quantitys[$key][$keys];
                    }
                }
            } else {
                if ($request->att == 'unit') {
                    //dd($request->unit);
                    foreach ($request->unit as $key => $units) {
                        //dd($request->unit[$key]);
                        $upkey = $key + 1;
                        if (isset($request->quantitys[$upkey]) && $request->quantitys[$upkey] != "") {
                            $qut = $qut + (int)$request->quantitys[$upkey];
                        }
                    }
                } elseif ($request->att == 'size') {
                    foreach ($request->quantityss as $key => $qty) {
                        if ($request->quantityss[$key] != "") {
                            $qut = $qut + (int)$request->quantityss[$key];
                        }
                    }
                } elseif ($request->att == 'onlycolor') {
                    foreach ($request->quantitysss as $key => $qty) {
                        $qut = $qut + (int)$request->quantitysss[$key];
                    }
                }
            }
            $veriant = Veriant::where('pid', $id)->get();
            if (isset($veriant) && count($veriant) > 0) {
                foreach ($veriant as $vsf) {
                    $qut1 = $qut1 + (int)$vsf->quantity;
                }
            }
            $qut2 = $qut + $qut1;

            if ($qut2 > (int)$request->quantity) {
                Session::flash('error', 'Product variant quantity exited !');
                return back();
            } else {
                $product = Product::find($id);
                $product->name = $request->product_name;
                $product->description = $request->description;
                $product->regular_price = $request->regular_price;
                $product->discount_type = $request->discount_type;
                $product->promotional_price = $request->promotional_price;
                $product->tax_type = $request->tax_type;
                $product->tax_rate = $request->tax_rate;
                $product->quantity = $request->quantity;
                $product->seo_keywords = $request->seo;
                $product->weight = $request->weight;
                $product->shipping_fee = $request->shipping_fee;
                $product->brand = $request->brand;
                $product->supplier = $request->supplier;
                $product->cost = $request->cost;
                $product->barcode = $request->barcode;
                if ($request->image) {
                    foreach ($request->image as $key => $image) {
                        $imgName = Carbon::now()->timestamp . $key . '.' . $image->extension();
                        $image->storeAs('product', $imgName);
                        $imagesname[] = $imgName;
                    }
                    $product->images = implode(',', $imagesname);
                }
                $product->category = $request->category;
                $product->subcategory = $request->subcategory;
                $product->tags = $request->tagss;
                $product->status = "active";
                $product->SKU = $request->SKU;
                if (isset($request->best_sell)) {
                    $product->best_sell = 1;
                } else {
                    $product->best_sell = 0;
                }
                if (isset($request->feature)) {
                    $product->feature = 1;
                } else {
                    $product->feature = 0;
                }
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
                $product->editor = $user;
                $product->save();
                if ($request->att == 'unit') {
                    //dd($request->unit);
                    foreach ($request->unit as $key => $units) {
                        //dd($request->unit[$key]);
                        $upkey = $key + 1;
                        if (isset($request->quantitys[$upkey]) && $request->quantitys[$upkey] != "") {
                            $variant = new Veriant;
                            $variant->pid = $product->id;
                            $variant->unit = $request->unit[$key];
                            $variant->volume = $request->volume[$key];
                            $variant->quantity = $request->quantitys[$upkey];
                            $variant->additional_price = $request->price[$upkey];
                            $variant->save();
                        }
                    }
                } elseif ($request->att == 'size') {
                    foreach ($request->quantityss as $key => $qty) {
                        if ($request->quantityss[$key] != "") {
                            $variant = new Veriant;
                            $variant->pid = $product->id;
                            $variant->size = $request->sizess[$key];
                            $variant->quantity = $request->quantityss[$key];
                            $variant->additional_price = $request->pricess[$key];
                            $variant->save();
                        }
                    }
                }
                if ($request->att == 'onlycolor') {
                    foreach ($request->quantitysss as $key => $qty) {
                        $variant = new Veriant;
                        $variant->pid = $product->id;
                        $variant->color = $request->colors[$key];
                        $variant->quantity = $request->quantitysss[$key];
                        $variant->additional_price = $request->pricesss[$key];
                        $variant->save();
                    }
                }
                if (isset($request->sid)) {
                    foreach ($request->sid as $key => $sids) {
                        foreach ($sids as $keys => $size) {
                            $veriant = new Veriant;
                            if ($key != '0') {
                                $keyss = $key . '1';
                                $veriant->color = $request->color[$keyss][0];
                            } else {
                                $veriant->color = $request->color[$key][0];
                            }
                            $veriant->pid = $product->id;
                            $veriant->size = $request->size[$key][$keys];
                            $veriant->quantity = $request->quantitys[$key][$keys];
                            $veriant->additional_price = $request->price[$key][$keys];
                            $veriant->save();
                        }
                    }
                }
                $activity = " Update Product";
                $this->saveactivity($activity);
                Session::flash('message', 'Product Updated Successfully !');
                return redirect('/products');
            }
        }
    }

    public function removeimage($id, $image)
    {
        // dd($image);
        $product = Product::find($id);
        // $img=str_replace(",".$image,"",$product->images);
        // $product->images=$img;
        $p = explode(',', $product->images);
        $b = array_diff($p, [$image]);
        $c = implode(',', $b);
        $product->images = $c;
        $product->save();
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
        $activity = " Remove Image From Product";
        $this->saveactivity($activity);
        return back();
    }

    public function inventory()
    {
        $product = $this->checkroleinv();
        if (isset($product) && $product == '1' || Auth::user()->type == 'admin' || Auth::user()->type == 'dropshipper') {
            $urls = "inventory";
            $url1 = "Inventory";
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
            $toptool = Toptool::where('name', 'Inventory')->where('uid', $user)->where('store_id', $store_id)->first();
            if (isset($toptool)) {
                $toptool->count = $toptool->count + 1;
                $toptool->save();
            } else {
                $toptool = new Toptool();
                $toptool->name = "Inventory";
                $toptool->image = "inventory-2.png";
                $toptool->url = "/inventory";
                $toptool->count = "1";
                $toptool->uid = $user;
                $toptool->store_id = $store_id;
                $toptool->customer_id = $customer_id;
                $toptool->creator = $user;
                $toptool->editor = $user;
                $toptool->save();
            }
            $activity = " Access Inventory Page";
            $this->saveactivity($activity);
            $products = Product::where('store_id', $store_id)->where('quantity', '!=', '0')->where('status', '!=',
                'RecycleBin')->orderby('id', 'DESC')->get();
            return view('admin.product.inventory')->with('urls', $urls)->with('products', $products)->with('url1',
                $url1);
        } else {
            return redirect()->route('admin.index');
        }
    }

    public function checkroleinv()
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
                    } elseif ($pr == 'inventory') {
                        $inv = 1;
                        return $inv;
                    } else {

                    }
                }
            }
        }
    }

    public function stockalert()
    {
        $product = $this->checkroleinv();
        if (isset($product) && $product == '1' || Auth::user()->type == 'admin' || Auth::user()->type == 'dropshipper') {
            $urls = "inventory";
            $url1 = "Stock Alert";
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
            $toptool = Toptool::where('name', 'Stockalert')->where('uid', $user)->where('store_id', $store_id)->first();
            if (isset($toptool)) {
                $toptool->count = $toptool->count + 1;
                $toptool->save();
            } else {
                $toptool = new Toptool();
                $toptool->name = "Stockalert";
                $toptool->image = "new-product.png";
                $toptool->url = "/stock_alert";
                $toptool->count = "1";
                $toptool->uid = $user;
                $toptool->store_id = $store_id;
                $toptool->customer_id = $customer_id;
                $toptool->creator = $user;
                $toptool->editor = $user;
                $toptool->save();
            }
            $activity = " Access Stock Alert Page";
            $this->saveactivity($activity);
            $products = Product::where('store_id', $store_id)->where('quantity', '<=', '5')->where('status', '!=',
                'RecycleBin')->orderby('id', 'DESC')->get();
            return view('admin.product.inventory')->with('urls', $urls)->with('products', $products)->with('url1',
                $url1);
        } else {
            return redirect()->route('admin.index');
        }
    }

    public function stockout()
    {
        $product = $this->checkroleinv();
        if (isset($product) && $product == '1' || Auth::user()->type == 'admin' || Auth::user()->type == 'dropshipper') {
            $urls = "inventory";
            $url1 = "Stock Out";
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
            $toptool = Toptool::where('name', 'Stockout')->where('uid', $user)->where('store_id', $store_id)->first();
            if (isset($toptool)) {
                $toptool->count = $toptool->count + 1;
                $toptool->save();
            } else {
                $toptool = new Toptool();
                $toptool->name = "Stockout";
                $toptool->image = "out-of-stock.png";
                $toptool->url = "/stock_out";
                $toptool->count = "1";
                $toptool->uid = $user;
                $toptool->store_id = $store_id;
                $toptool->customer_id = $customer_id;
                $toptool->creator = $user;
                $toptool->editor = $user;
                $toptool->save();
            }
            $activity = " Access Stock Out Page";
            $this->saveactivity($activity);
            $products = Product::where('store_id', $store_id)->where('quantity', '<=', '0')->where('status', '!=',
                'RecycleBin')->orderby('id', 'DESC')->get();
            return view('admin.product.inventory')->with('urls', $urls)->with('products', $products)->with('url1',
                $url1);
        } else {
            return redirect()->route('admin.index');
        }
    }

    public function changeproductstatus(Request $request)
    {

        // dd($request->all());
        if ($request->text2 == '') {
            Session::flash('message', 'Please Select Product');
            return back();
        }
        if ($request->action == 'select') {
            Session::flash('message', 'Please Select a Option');
            return back();
        }

        if ($request->action == 'active') {
            $id = explode(',', $request->text2);
            if (isset($id) && count($id) > 0) {
                foreach ($id as $ids) {
                    $product = Product::find($ids);
                    $product->status = 'active';
                    $product->save();
                }
            }
            Session::flash('message', 'Successfully Active Product');
            return back();
        }
        if ($request->action == 'deactive') {
            $id = explode(',', $request->text2);
            if (isset($id) && count($id) > 0) {
                foreach ($id as $ids) {
                    $product = Product::find($ids);
                    $product->status = 'deactive';
                    $product->save();
                }
            }
            Session::flash('message', 'Successfully Deactive Product');
            return back();
        }
        if ($request->action == 'delete') {
            $id = explode(',', $request->text2);
            if (isset($id) && count($id) > 0) {
                foreach ($id as $ids) {
                    $product = Product::find($ids);
                    $product->status = 'RecycleBin';
                    $product->save();
                }
            }
            Session::flash('message', 'Successfully Deleted Product');
            return back();
        }
    }
}
