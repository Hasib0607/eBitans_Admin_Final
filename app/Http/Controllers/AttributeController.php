<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Color;
use App\Models\Unit;
use App\Models\Size;
use Illuminate\Support\Facades\DB;
use Session;
use App\Models\Staff;
use App\Models\Store;
use App\Models\Role;
use App\Models\Customer;
use App\Models\Toptool;
use App\Models\Activitylog;
use App\Http\Traits\ActivityLogTraits;
use Auth;

class AttributeController extends Controller
{
    use ActivityLogTraits;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $attribute = $this->checkrole();
        if (isset($attribute) && $attribute == '1' || Auth::user()->type == 'admin' || Auth::user()->type == 'dropshipper') {
            $urls = "product";
            $user = Auth::user()->id;
            $userData = getUserData();
            $store_id = $userData['store_id'];
            $customer_id = $userData['customer_id'];

            $toptool = Toptool::where('name', 'Attribute')->where('uid', $user)->where('store_id', $store_id)->first();
            if (isset($toptool)) {
                $toptool->count = $toptool->count + 1;
                $toptool->save();
            } else {
                $toptool = new Toptool();
                $toptool->name = "Attribute";
                $toptool->image = "product.png";
                $toptool->url = "/attribute";
                $toptool->count = "1";
                $toptool->uid = $user;
                $toptool->store_id = $store_id;
                $toptool->customer_id = $customer_id;
                $toptool->creator = $user;
                $toptool->editor = $user;
                $toptool->save();
            }
            $activity = " Access Attribute Color Page";
            $this->saveactivity($activity);
            $color = Color::where('store_id', $store_id)->orderBy('position', 'asc')->get();
            return view('admin.attribute.index')->with('color', $color)->with('urls', $urls);
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
                        return $attribute;
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
    }

    public function size()
    {
        $attribute = $this->checkrole();
        if (isset($attribute) && $attribute == '1' || Auth::user()->type == 'admin' || Auth::user()->type == 'dropshipper') {
            $urls = "product";
            $user = Auth::user()->id;

            $userData = getUserData();
            $store_id = $userData['store_id'];
            $customer_id = $userData['customer_id'];

            $toptool = Toptool::where('name', 'Attribute')->where('uid', $user)->where('store_id', $store_id)->first();
            if (isset($toptool)) {
                $toptool->count = $toptool->count + 1;
                $toptool->save();
            } else {
                $toptool = new Toptool();
                $toptool->name = "Attribute";
                $toptool->image = "product.png";
                $toptool->url = "/attribute";
                $toptool->count = "1";
                $toptool->uid = $user;
                $toptool->store_id = $store_id;
                $toptool->customer_id = $customer_id;
                $toptool->creator = $user;
                $toptool->editor = $user;
                $toptool->save();
            }
            $size = Size::where('store_id', $store_id)->orderBy('position', 'asc')->get();
            $activity = " Access Attribute Size Page";
            $this->saveactivity($activity);
            return view('admin.attribute.sizelist')->with('sizes', $size)->with('urls', $urls);
        }
    }

    public function savesize(Request $request)
    {
        $size = new Size;
        $size->name = $request->name;
        $user = Auth::user()->id;

        $userData = getUserData();
        $store_id = $userData['store_id'];
        $customer_id = $userData['customer_id'];

        $size->uid = $user;
        $size->customer_id = $customer_id;
        $size->store_id = $store_id;
        $size->creator = $user;
        $size->editor = $user;
        $size->save();
        $activity = " Save Size Attribute";
        $this->saveactivity($activity);
        Session()->flash('success_message', 'Size Save Successfully!');
        return back();
    }

    public function deletesize($id)
    {
        $userData = getUserData();
        $store_id = $userData['store_id'];

        $attribute = $this->checkrole();
        if (isset($attribute) && $attribute == '1' || Auth::user()->type == 'admin' || Auth::user()->type == 'dropshipper') {
            $size = Size::find($id);
            $size = Size::where('store_id', $store_id)->where('id', $id)->first();
            if (empty($size)) {
                return back();
            }

            $activity = " Delete Size Attribute";
            $this->saveactivity($activity);
            $size->delete();
            Session()->flash('success_message', 'Size Delete Successfully!');
            return back();
        }
    }

    public function unit()
    {
        $attribute = $this->checkrole();
        if (isset($attribute) && $attribute == '1' || Auth::user()->type == 'admin' || Auth::user()->type == 'dropshipper') {
            $urls = "product";
            $user = Auth::user()->id;

            $userData = getUserData();
            $store_id = $userData['store_id'];
            $customer_id = $userData['customer_id'];

            $toptool = Toptool::where('name', 'Attribute')->where('uid', $user)->where('store_id', $store_id)->first();
            if (isset($toptool)) {
                $toptool->count = $toptool->count + 1;
                $toptool->save();
            } else {
                $toptool = new Toptool();
                $toptool->name = "Attribute";
                $toptool->image = "product.png";
                $toptool->url = "/attribute";
                $toptool->count = "1";
                $toptool->uid = $user;
                $toptool->store_id = $store_id;
                $toptool->customer_id = $customer_id;
                $toptool->creator = $user;
                $toptool->editor = $user;
                $toptool->save();
            }
            $activity = " Access Attribute Unit Page";
            $this->saveactivity($activity);
            $units = Unit::where('store_id', $store_id)->get();
            return view('admin.attribute.unitlist')->with('units', $units)->with('urls', $urls);
        }
    }

    public function saveunit(Request $request)
    {
        $unit = new Unit;
        $unit->name = $request->name;
        $user = Auth::user()->id;

        $userData = getUserData();
        $store_id = $userData['store_id'];
        $customer_id = $userData['customer_id'];

        $unit->uid = $user;
        $unit->customer_id = $customer_id;
        $unit->store_id = $store_id;
        $unit->creator = $user;
        $unit->editor = $user;
        $unit->save();
        $activity = " Save unit attribute";
        $this->saveactivity($activity);
        Session()->flash('success_message', 'unit Save Successfully!');
        return back();
    }

    public function deleteunit($id)
    {
        $userData = getUserData();
        $store_id = $userData['store_id'];

        $attribute = $this->checkrole();
        if (isset($attribute) && $attribute == '1' || Auth::user()->type == 'admin' || Auth::user()->type == 'dropshipper') {
            $unit = unit::find($id);
            $unit = unit::where('store_id', $store_id)->where('id', $id)->first();
            if (empty($unit)) {
                return back();
            }
            $activity = " Delete Unit Attribute " . $unit->name;
            $this->saveactivity($activity);
            $unit->delete();
            Session()->flash('success_message', 'unit Delete Successfully!');
            return back();
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function savecolor(Request $request)
    {
        $user = Auth::user()->id;

        $userData = getUserData();
        $store_id = $userData['store_id'];
        $customer_id = $userData['customer_id'];

        $toptool = Toptool::where('name', 'Attribute')->where('uid', $user)->where('store_id', $store_id)->first();
        if (isset($toptool)) {
            $toptool->count = $toptool->count + 1;
            $toptool->save();
        } else {
            $toptool = new Toptool();
            $toptool->name = "Attribute";
            $toptool->image = "product.png";
            $toptool->url = "/attribute";
            $toptool->count = "1";
            $toptool->uid = $user;
            $toptool->store_id = $store_id;
            $toptool->customer_id = $customer_id;
            $toptool->creator = $user;
            $toptool->editor = $user;
            $toptool->save();
        }
        $color = new Color;
        $color->name = $request->color_name;
        $color->code = $request->color;
        $color->uid = $user;
        $color->customer_id = $customer_id;
        $color->store_id = $store_id;
        $color->creator = $user;
        $color->editor = $user;
        $color->save();
        Session()->flash('success_message', 'Color Save Successfully!');
        return back();
        $activity = " Save Color Attribute";
        $this->saveactivity($activity);
    }

    public function deletecolor($id)
    {
        $userData = getUserData();
        $store_id = $userData['store_id'];

        $color = Color::where('store_id', $store_id)->where('id', $id)->first();
        if (empty($color)) {
            return back();
        }

        $color->delete();
        Session()->flash('success_message', 'Color Delete Successfully!');
        return back();
    }

    public function position(Request $request)
    {
        // dd($request->id);
        // $user = Auth::user()->id;
        // $user_type = Auth::user()->type;
        // if ($user_type == "admin" || $user_type == "dropshipper") {
        //     $customer = Customer::where('uid', $user)->first();
        //     $store_id = $customer->active_store;
        //     $customer_id = $customer->id;
        // } elseif ($user_type == 'staff') {
        //     $staff = Staff::where('uid', Auth::user()->id)->first();
        //     $store_id = $staff->store_id;
        //     $customer_id = $staff->customer_id;
        // }
        $color = Color::find($request->id);
        if (empty($color)) {
            return back();
        }

        $color->position = $request->position;
        $color->save();
        Session()->flash('success_message', 'Color position updated Successfully!');
        return back();
    }

    public function size_position(Request $request)
    {
        // dd($request->id);
        // $user = Auth::user()->id;
        // $user_type = Auth::user()->type;
        // if ($user_type == "admin" || $user_type == "dropshipper") {
        //     $customer = Customer::where('uid', $user)->first();
        //     $store_id = $customer->active_store;
        //     $customer_id = $customer->id;
        // } elseif ($user_type == 'staff') {
        //     $staff = Staff::where('uid', Auth::user()->id)->first();
        //     $store_id = $staff->store_id;
        //     $customer_id = $staff->customer_id;
        // }
        $size = Size::find($request->id);
        if (empty($size)) {
            return back();
        }

        $size->position = $request->position;
        $size->save();
        Session()->flash('success_message', 'Size position updated Successfully!');
        return back();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
