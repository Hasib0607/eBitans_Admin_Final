<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HomePae;
use Session;
use Auth;
use App\Models\Customer;
use App\Models\Store;
use App\Models\Staff;
use App\Models\Role;
use App\Models\Designlist;
use App\Models\Menu;
use App\Models\Design;

class LayoutController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function homepage()
    {
        $layouts = $this->checkrole();
        if (isset($layouts) && $layouts == "1" || Auth::user()->type == 'admin' || Auth::user()->type == 'dropshipper') {
            $urls = "design";
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
            $store = Store::where('id', $store_id)->first();
            $design = Designlist::where('type', 'invoice')->get();
//            $homepage = HomePae::where('store_id', $store_id)->first();
            return view('admin.design.layouts.homepage')->with('homepage', $homepage)->with('urls', $urls)->with('store', $store)->with('design', $design)->with('store_id', $store_id);
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
                        return $layouts;
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

    public function saveinvoice(Request $request)
    {
        $urls = "design";
        $menu = Menu::all();
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
        $desig = Design::where('store_id', $store_id)->first();
        if (isset($desig)) {
            $design = Design::find($desig->id);
            if ($request->invoice == "null") {
                $design->invoice = null;
            } else {
                $design->invoice = $request->invoice;
            }
            $design->save();
        } else {
            $design = new Design;
            if ($request->invoice == "null") {
                $design->invoice = null;
            } else {
                $design->invoice = $request->invoice;
            }
            $design->uid = $user;
            $design->customer_id = $customer_id;
            $design->store_id = $store_id;
            $design->creator = $user;
            $design->editor = $user;
            $design->save();
        }
        Session::flash('message', 'Invoice Design Successfully !');
        return back();
    }

    public function savehomepage(Request $request)
    {
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
        $homepage = homepae::where('store_id', $store_id)->first();
        if (isset($homepage)) {
            if ($request->slider == '1') {
                $homepage->slider = 'active';
            } else {
                $homepage->slider = 'inactive';
            }
            if ($request->banner == '1') {
                $homepage->banner = 'active';
            } else {
                $homepage->banner = 'inactive';
            }
            if ($request->new_arrival == '1') {
                $homepage->new_arrival = 'active';
            } else {
                $homepage->new_arrival = 'inactive';
            }
            if ($request->offer == '1') {
                $homepage->offer = 'active';
            } else {
                $homepage->offer = 'inactive';
            }
            if ($request->trends_product == '1') {
                $homepage->trends_product = 'active';
            } else {
                $homepage->trends_product = 'inactive';
            }
            if ($request->client_section == '1') {
                $homepage->client_section = 'active';
            } else {
                $homepage->client_section = 'inactive';
            }
            if ($request->testimonials == '1') {
                $homepage->testimonials = 'active';
            } else {
                $homepage->testimonials = 'inactive';
            }
            if ($request->newslatter == '1') {
                $homepage->newslatter = 'active';
            } else {
                $homepage->newslatter = 'inactive';
            }
            if ($request->privacy_policy == '1') {
                $homepage->privacy_policy = 'active';
            } else {
                $homepage->privacy_policy = 'inactive';
            }
            $homepage->editor = $user;
            $homepage->save();
        } else {
            $homepage = new Homepae();
            if ($request->slider == '1') {
                $homepage->slider = 'active';
            } else {
                $homepage->slider = 'inactive';
            }
            if ($request->banner == '1') {
                $homepage->banner = 'active';
            } else {
                $homepage->banner = 'inactive';
            }
            if ($request->new_arrival == '1') {
                $homepage->new_arrival = 'active';
            } else {
                $homepage->new_arrival = 'inactive';
            }
            if ($request->offer == '1') {
                $homepage->offer = 'active';
            } else {
                $homepage->offer = 'inactive';
            }
            if ($request->trends_product == '1') {
                $homepage->trends_product = 'active';
            } else {
                $homepage->trends_product = 'inactive';
            }
            if ($request->client_section == '1') {
                $homepage->client_section = 'active';
            } else {
                $homepage->client_section = 'inactive';
            }
            if ($request->testimonials == '1') {
                $homepage->testimonials = 'active';
            } else {
                $homepage->testimonials = 'inactive';
            }
            if ($request->newslatter == '1') {
                $homepage->newslatter = 'active';
            } else {
                $homepage->newslatter = 'inactive';
            }
            if ($request->privacy_policy == '1') {
                $homepage->privacy_policy = 'active';
            } else {
                $homepage->privacy_policy = 'inactive';
            }
            $homepage->uid = $user;
            $homepage->store_id = $store_id;
            $homepage->customer_id = $customer_id;
            $homepage->creator = $user;
            $homepage->editor = $user;
            $homepage->save();
        }

        Session::flash('success_message', 'Home Page Content Updated Successfully !');
        return back();
    }
}
