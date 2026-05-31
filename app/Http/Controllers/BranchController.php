<?php

namespace App\Http\Controllers;

use App\Models\AddonsExpired;
use App\Models\ProductTransfer;
use Illuminate\Http\Request;
use App\Models\Brand;
use App\Models\Branch;
use App\Models\Staff;
use App\Models\Product;
use App\Models\Branchproduct;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Validator;
use Session;
use Cart;
use Auth;
use App\Models\Customer;
use App\Models\Role;
use App\Models\Toptool;
use App\Models\Superstaff;
use App\Models\Superrole;
use App\Models\Activitylog;
use App\Http\Traits\ActivityLogTraits;
use App\Models\Plan;
use App\Models\Store;
use App\Models\Posplan;
use DB;

class BranchController extends Controller
{
    use ActivityLogTraits;

    public function __construct()
    {
        $this->middleware('auth');
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
                        return $branch;
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
                    } elseif ($pr == 'pos') {
                        $pos = 1;
                    } else {

                    }
                }
            }
        }
    }

    public function checkroledelbranch()
    {
        if (Auth::user()->type == 'superstaff') {
            $superstaff = Superstaff::where('uid', Auth::user()->id)->first();
            $superrole = Superrole::where('id', $superstaff->role_id)->first();
            $permissionss = explode(',', $superrole->permission);
            foreach ($permissionss as $key => $prs) {
                if ($prs == 'branch_delete_request') {
                    $branch_delete_request = 1;
                    return $branch_delete_request;
                } else {

                }
            }
        }
    }

    public function index()
    {
        $urls = "branch";
        $user = Auth::user()->id;

        //user_id, store_id, customer, customer_id
        extract(getUserData());

        $toptool = Toptool::where('name', 'Branch')->where('uid', $user)->where('store_id', $store_id)->first();
        if (isset($toptool)) {
            $toptool->count = $toptool->count + 1;
            $toptool->save();
        } else {
            $toptool = new Toptool();
            $toptool->name = "Branch";
            $toptool->image = "branch.png";
            $toptool->url = "/branch";
            $toptool->count = "1";
            $toptool->uid = $user;
            $toptool->store_id = $store_id;
            $toptool->customer_id = $customer_id;
            $toptool->creator = $user;
            $toptool->editor = $user;
            $toptool->save();
        }
        $branchs = Branch::where('store_id', $store_id)->get();
        $activity = " Access Branch Page";
        $this->saveactivity($activity);

        $currentDate = Carbon::now();
        $posAddon = AddonsExpired::where("store_id", $store_id)
            ->where("addons_id", 13)
            ->where('expired_date', ">=", $currentDate)
            ->first();

        $pos_plan_id = $posAddon->pos_plan_id ?? "";
        $plan = Posplan::where('id', $pos_plan_id)->first();

        if ($plan->branch >= count($branchs)) {
            return view('admin.branch.index')->with('plan', $plan)->with('branchs', $branchs)->with('urls', $urls);
        } else {
            return redirect()->route('admin.index');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $urls = "branch";
        //user_id, store_id, customer, customer_id
        extract(getUserData());

        $toptool = Toptool::where('name', 'Branch')->where('uid', $user)->where('store_id', $store_id)->first();
        if (isset($toptool)) {
            $toptool->count = $toptool->count + 1;
            $toptool->save();
        } else {
            $toptool = new Toptool();
            $toptool->name = "Branch";
            $toptool->image = "branch.png";
            $toptool->url = "/branch";
            $toptool->count = "1";
            $toptool->uid = $user;
            $toptool->store_id = $store_id;
            $toptool->customer_id = $customer_id;
            $toptool->creator = $user;
            $toptool->editor = $user;
            $toptool->save();
        }
        $activity = " Access Branch Create Page";
        $this->saveactivity($activity);
        return view('admin.branch.create')->with('urls', $urls);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //user_id, store_id, customer, customer_id
        extract(getUserData());

        $currentDate = Carbon::now();
        $posAddon = AddonsExpired::where("store_id", $store_id)
            ->where("addons_id", 13)
            ->where('expired_date', ">=", $currentDate)
            ->first();

        $pos_plan_id = $posAddon->pos_plan_id ?? "";
        $plan = Posplan::where('id', $pos_plan_id)->first();

        $branchss = Branch::where('store_id', $store_id)->get();
        if ($plan->branch <= count($branchss)) {
            Session::flash('error', 'Already Reached Branch Limit. If you want to add more branch you need to upgrade your plan');
            return back();
        }
        $rules = array(
            'name' => 'required',
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator);
        } else {
            $branch = new Branch;
            $branch->name = $request->name;
            $branch->email = $request->email;
            $branch->phone = $request->phone;
            $branch->address = $request->address;
            $branch->tax = $request->tax;
            $branch->uid = Auth::user()->id;


            $branch->customer_id = $customer_id;
            $branch->store_id = $store_id;
            $branch->creator = Auth::user()->id;
            $branch->editor = Auth::user()->id;
            $branch->status = "active";
            $branch->save();
            $activity = " Save Branch " . $branch->name;
            $this->saveactivity($activity);
            Session::flash('success_message', 'Branch Save Successfully !');
            return redirect()->route('admin.branch.index');
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
        $urls = "branch";
        $branch = Branch::find($id);
        //user_id, store_id, customer, customer_id
        extract(getUserData());

        $toptool = Toptool::where('name', 'Branch')->where('uid', $user)->where('store_id', $store_id)->first();
        if (isset($toptool)) {
            $toptool->count = $toptool->count + 1;
            $toptool->save();
        } else {
            $toptool = new Toptool();
            $toptool->name = "Branch";
            $toptool->image = "branch.png";
            $toptool->url = "/branch";
            $toptool->count = "1";
            $toptool->uid = $user;
            $toptool->store_id = $store_id;
            $toptool->customer_id = $customer_id;
            $toptool->creator = $user;
            $toptool->editor = $user;
            $toptool->save();
        }
        // $customer=Customer::where('uid',$user)->first();
        $staffs = Staff::where('customer_id', $customer_id)->get();
        $products = Product::where('store_id', $store_id)->where('status', 'active')->get();
        $bps = Branchproduct::where('branch_id', $id)->get();
        $activity = " Edit Branch " . $branch->name;
        $this->saveactivity($activity);
        return view('admin.branch.edit')->with('branch', $branch)->with('staffs', $staffs)->with('products', $products)->with('bps', $bps)->with('urls', $urls);
    }

    public function addproduct($id)
    {
        $urls = "branch";
        $branch = Branch::find($id);
        //user_id, store_id, customer, customer_id
        extract(getUserData());

        $toptool = Toptool::where('name', 'Add Product POS')->where('uid', $user)->where('store_id', $store_id)->first();
        if (isset($toptool)) {
            $toptool->count = $toptool->count + 1;
            $toptool->save();
        } else {
            $toptool = new Toptool();
            $toptool->name = "Add Product POS";
            $toptool->image = "branch.png";
            $toptool->url = "/branch";
            $toptool->count = "1";
            $toptool->uid = $user;
            $toptool->store_id = $store_id;
            $toptool->customer_id = $customer_id;
            $toptool->creator = $user;
            $toptool->editor = $user;
            $toptool->save();
        }
        // $customer=Customer::where('uid',$user)->first();
        $staffs = Staff::where('customer_id', $customer_id)->get();
        $bps = Branchproduct::where('branch_id', $id)->get();
        $products = Product::where('store_id', $store_id)
            ->where('status', 'active')
            ->whereNotIn('id', function ($query) use ($id) {
                $query->select('product_id')
                    ->from('branchproducts')
                    ->where('branch_id', $id);
            })
            ->get();

        $activity = " Access Branch Product Add Page";
        $this->saveactivity($activity);
        return view('admin.branch.addproduct')->with('branch', $branch)->with('staffs', $staffs)->with('products', $products)->with('bps', $bps)->with('urls', $urls);
    }

    public function savestafftobranch(Request $request)
    {

        if ($request->text2 != "") {
            $text = explode(',', $request->text2);

            if (count($text) > 0) {
                $branch = Branch::find($request->branchid);
                $branch->staff_id = $request->text2;
                $branch->save();
            }
        }
        $activity = " Access Branch Staff Add Page";
        $this->saveactivity($activity);
        return back();
    }

    public function removeformbranch($bid, $id)
    {
        //user_id, store_id, customer, customer_id
        extract(getUserData());

        $brnch = Branch::find($bid);
        $p = explode(',', $brnch->staff_id);
        $b = array_diff($p, [$id]);
        $c = implode(',', $b);
        $brnch->staff_id = $c;
        $brnch->save();
        $activity = " Remove Product From Branch";
        $this->saveactivity($activity, $store_id);
        return back();
    }

    public function deleteproductfrombranch($id)
    {
        $bps = Branchproduct::find($id);
        $bps->delete();
        $activity = " Delete Product From Branch";
        $this->saveactivity($activity);
        return back();
    }

    public function updateinventoryquantity(Request $request)
    {
        if (isset($request->bid)) {
            if (count($request->bid) > 0) {
                foreach ($request->bid as $key => $id) {
                    $bps = Branchproduct::find($id);
                    $bps->quantity = $request->qty[$key];
                    $bps->editor = Auth::user()->id;
                    $bps->save();
                }
            }
        }
        $activity = " Update Inventory Quantity";
        $this->saveactivity($activity);

        return back();
    }

    public function productTransfer(Request $request)
    {
        $userData = getUserData();
        $storeId = $userData['store_id'];

        $branch_product_id = $request->branch_product_id;
        $toBranch = (int)$request->toBranch;
        $quantity = (float)$request->quantity;

        $branchProduct = Branchproduct::where("id", $branch_product_id)->first();

        if (!isset($branchProduct)) {
            return back()->with("error", "Branch Product not found!");
        }

        $currentQty = (float)$branchProduct->quantity;
        $fromBranch = (int)$branchProduct->branch_id;
        $product_id = $branchProduct->product_id;
        $uid = $branchProduct->uid;
        $creator = $branchProduct->creator;
        $editor = $branchProduct->editor;
        $customer_id = $branchProduct->customer_id;

        if ($fromBranch === $toBranch) {
            return back()->with("error", "Old Branch and Transfer Branch Can not be same!");
        } else if ($currentQty < $quantity) {
            return back()->with("error", "Transfer Quantity Can not be Greater Than Actual Quantity!");
        } else if ($quantity <= 0) {
            return back()->with("error", "Transfer Quantity Can not be Zero!!");
        }

        $newBranchProduct = Branchproduct::where("product_id", $product_id)->where("branch_id", $toBranch)->first();
        if (isset($newBranchProduct)) {
            $newQty = (float)$newBranchProduct->quantity + $quantity;
            $newBranchProduct->quantity = $newQty;
            $newBranchProduct->update();
        } else {
            $newBranchProduct = new Branchproduct();
            $newBranchProduct->product_id = $product_id;
            $newBranchProduct->quantity = $quantity;
            $newBranchProduct->branch_id = $toBranch;
            $newBranchProduct->uid = $uid;
            $newBranchProduct->creator = $creator;
            $newBranchProduct->editor = $editor;
            $newBranchProduct->customer_id = $customer_id;
            $newBranchProduct->save();
        }


        $newQty = $currentQty - $quantity;
        $branchProduct->quantity = $newQty;
        $branchProduct->update();

        $productTransfer = new ProductTransfer();
        $productTransfer->product_id = $product_id;
        $productTransfer->from_branch = $fromBranch;
        $productTransfer->to_branch = $toBranch;
        $productTransfer->old_qty = $currentQty;
        $productTransfer->transfer_qty = $quantity;
        $productTransfer->store_id = $storeId;
        $productTransfer->save();

        return back()->with("success", "Product Transfer Successfully");
    }

    public function deletebranch($id)
    {
        $branch = Branch::find($id);
        $activity = " Delete Branch " . $branch->name;
        $this->saveactivity($activity);
        $branch->status = "delreq";
        $branch->save();
        Session::flash('success_message', 'Branch Deleted Request Send. Its Take 48 hours. Thank you !');
        return redirect()->route('admin.branch.index');
    }

    public function branchdel()
    {
        $roless = $this->checkroledelbranch();
        if (isset($roless) && $roless == "1" || Auth::user()->type == 'superadmin') {
            $urls = "branchdel";
            $branch = Branch::where('status', 'delreq')->get();
            return view('admin.branch.delreq')->with('branchs', $branch)->with('urls', $urls);
        } else {
            return redirect()->route('superadmin.index');
        }
    }

    public function restoredeletebranch($id)
    {
        $branch = Branch::find($id);
        $branch->status = "active";
        $branch->save();
        Session::flash('success_message', ' Branch Restore Successfully !');
        return back();
    }

    public function saveproducttobranch(Request $request)
    {
        if (isset($request->selectedid1)) {
            if (count($request->selectedid1) > 0) {
                foreach ($request->selectedid1 as $id) {
                    $bpp = Branchproduct::where('product_id', $id)->where('branch_id', $request->branchid)->first();
                    if (!isset($bpp)) {
                        $product = Product::where('id', $id)->first();

                        $bp = new Branchproduct();
                        $bp->product_id = $id;
                        $bp->quantity = $product->quantity ?? NULL;
                        $bp->branch_id = $request->branchid;
                        $bp->uid = Auth::user()->id;
                        $bp->creator = Auth::user()->id;
                        $customer = Customer::where('uid', Auth::user()->id)->first();
                        $bp->customer_id = $customer->id;
                        $bp->save();
                    }
                }
            }
        }
        $activity = " Save Product to Branch";
        $this->saveactivity($activity);
        return back();
    }

    public function superadmindeletebranch($id)
    {
        $branch = Branch::find($id);
        $branchproduct = Branchproduct::where('branch_id', $branch->id)->get();
        if (count($branchproduct) > 0) {
            foreach ($branchproduct as $bp) {
                $bps = Branchproduct::find($bp->id);
                $bps->delete();
            }
        }
        $branch->delete();
        Session::flash('success_message', ' Branch Deleted Successfully !');
        return back();
    }

    public function pos($id)
    {
        // dd(Cart::instance('cart')->content());
        $id = decrypt($id);
        $branch = Branch::find($id);

        //user_id, store_id, customer, customer_id
        extract(getUserData());

        if ($branch->store_id == $store_id) {
            $bps = Branchproduct::where('branch_id', $branch->id)->get();
            if (count($bps) > 0) {
                foreach ($bps as $bp) {
                    $product_id[] = $bp->product_id;
                }
            }
            if (isset($product_id) && count($product_id) > 0) {
                foreach ($product_id as $pid) {
                    $product = Product::where('id', $pid)->first();
                    if (isset($product)) {
                        $products[] = $product;
                    }
                }
            } else {
                $products = null;
            }
            $branch_id = $id;

            return view('welcome')->with('products', $products)->with('branch_id', $branch_id);
        } else {
            return redirect('/login');
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
            $branch = Branch::find($id);
            $branch->name = $request->name;
            $branch->email = $request->email;
            $branch->phone = $request->phone;
            $branch->address = $request->address;
            $branch->tax = $request->tax;
            $branch->editor = Auth::user()->id;
            $branch->save();
            $activity = " Branch Update " . $branch->name;
            $this->saveactivity($activity);
            Session::flash('success_message', 'Branch Update Successfully !');
            return redirect()->route('admin.branch.index');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $brand = Brand::find($id);
        $brand->delete();
        Session::flash('success_message', 'Brand Delete Successfully !');
        return redirect('brand');
    }

    public function changebranchssstatus(Request $request)
    {
        if ($request->text2 == '') {
            Session::flash('message', 'Please Select Branch');
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
                    $product = Branch::find($ids);
                    $product->status = 'active';
                    $product->save();
                }
            }
            Session::flash('message', 'Successfully Active Branch');
            return back();
        }
        if ($request->action == 'deactive') {
            $id = explode(',', $request->text2);
            if (isset($id) && count($id) > 0) {
                foreach ($id as $ids) {
                    $product = Branch::find($ids);
                    $product->status = 'deactive';
                    $product->save();
                }
            }
            Session::flash('message', 'Successfully Deactive Branch');
            return back();
        }
        if ($request->action == 'delete') {
            $id = explode(',', $request->text2);
            if (isset($id) && count($id) > 0) {
                foreach ($id as $ids) {
                    $product = Branch::find($ids);
                    $product->delete();
                }
            }
            Session::flash('message', 'Successfully Deleted Branch');
            return back();
        }
    }
}
