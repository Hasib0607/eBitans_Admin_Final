<?php

namespace App\Http\Controllers;

use App\Models\BlockUser;
use Illuminate\Http\Request;
use App\Models\Page;
use App\Models\User;
use App\Models\Address;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Validator;
use Session;
use Auth;
use App\Models\Staff;
use App\Models\Role;
use App\Models\Customer;
use App\Models\Toptool;
use Carbon\Carbon;
use App\Models\Activitylog;
use App\Http\Traits\ActivityLogTraits;
use App\Models\NewsLetter;
use App\Models\Order;

class CustomerController extends Controller
{
    use ActivityLogTraits;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {

        $cstmr = $this->checkrole();
        if (isset($cstmr) && $cstmr == "1" || Auth::user()->type == 'admin' || Auth::user()->type == 'dropshipper') {
            $urls = "customer";
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

            $toptool = Toptool::where('name', 'Customer')->where('uid', $user)->where('store_id', $store_id)->first();
            if (isset($toptool)) {
                $toptool->count = $toptool->count + 1;
                $toptool->save();
            } else {
                $toptool = new Toptool();
                $toptool->name = "Customer";
                $toptool->image = "rating.png";
                $toptool->url = "/customer";
                $toptool->count = "1";
                $toptool->uid = $user;
                $toptool->store_id = $store_id;
                $toptool->customer_id = $customer_id;
                $toptool->creator = $user;
                $toptool->editor = $user;
                $toptool->save();
            }
            $list = User::where('type', 'customer')->where('store_id', $store_id)->orWhere('type', 'walking_customer')->orderBy('id', 'DESC')->paginate(30);
            $activity = " Access Customer List Page";
            $this->saveactivity($activity);
            return view('admin.customer.index')
                ->with('data', $list)->with('urls', $urls);
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
                        return $customer;
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

    public function newsLatter()
    {

        $cstmr = $this->checkrole();
        if (isset($cstmr) && $cstmr == "1" || Auth::user()->type == 'admin' || Auth::user()->type == 'dropshipper') {
            $urls = "news_latter";
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

            $toptool = Toptool::where('name', 'Customer')->where('uid', $user)->where('store_id', $store_id)->first();
            if (isset($toptool)) {
                $toptool->count = $toptool->count + 1;
                $toptool->save();
            } else {
                $toptool = new Toptool();
                $toptool->name = "Customer";
                $toptool->image = "rating.png";
                $toptool->url = "/customer/news-letter";
                $toptool->count = "1";
                $toptool->uid = $user;
                $toptool->store_id = $store_id;
                $toptool->customer_id = $customer_id;
                $toptool->creator = $user;
                $toptool->editor = $user;
                $toptool->save();
            }
            // dd('news letter');
            $list = NewsLetter::where('store_id', $store_id)->orderBy('id', 'DESC')->paginate(50);
            $activity = " Access Customer News Letter List Page";
            $this->saveactivity($activity);
            return view('admin.customer.news_letter')
                ->with('data', $list)->with('urls', $urls);
        }
    }

    public function customerexport(Request $request)
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
        $fileName = 'customer(' . $date . ').csv';
        $coupon = User::where('type', 'customer')->where('store_id', $store_id)->orWhere('type', 'walking_customer')->get();

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
        $activity = " Export Customer List";
        $this->saveactivity($activity);
        return response()->stream($callback, 200, $headers);
    }

    public function create()
    {
        $cstmr = $this->checkrole();
        if (isset($cstmr) && $cstmr == "1" || Auth::user()->type == 'admin' || Auth::user()->type == 'dropshipper') {
            $urls = "customer";
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
            $toptool = Toptool::where('name', 'Customer')->where('uid', $user)->where('store_id', $store_id)->first();
            if (isset($toptool)) {
                $toptool->count = $toptool->count + 1;
                $toptool->save();
            } else {
                $toptool = new Toptool();
                $toptool->name = "Customer";
                $toptool->image = "rating.png";
                $toptool->url = "/customer";
                $toptool->count = "1";
                $toptool->uid = $user;
                $toptool->store_id = $store_id;
                $toptool->customer_id = $customer_id;
                $toptool->creator = $user;
                $toptool->editor = $user;
                $toptool->save();
            }
            $activity = " Access Create Customer Page";
            $this->saveactivity($activity);
            return view('admin.customer.create')->with('urls', $urls);
        }
    }

    public function store(Request $request)
    {
        $rules = array(
            'name' => 'required',
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            Session::flash('message', 'Name and Title Required');
            return redirect()->back()
                ->withErrors($validator);
        } else {
            $customer = new User();
            $customer->name = $request->name;
            $customer->email = $request->email;
            $customer->phone = $request->phone;
            $customer->password = Hash::make($request->password);
            $customer->type = 'customer';
            $customer->address = $request->address;
            $user = Auth::user()->id;
            $user_type = Auth::user()->type;
            if ($user_type == "admin" || $user_type == "dropshipper") {
                $customerss = Customer::where('uid', $user)->first();
                $store_id = $customerss->active_store;
                $customer_id = $customerss->id;
            } elseif ($user_type == 'staff') {
                $staff = Staff::where('uid', $user)->first();
                $store_id = $staff->store_id;
                $customer_id = $staff->customer_id;
            }
            // $customer->uid=$user;
            $customer->customer_id = $customer_id;
            $customer->store_id = $store_id;
            // $customer->creator=$user;
            // $customer->editor=$user;
            if ($request->image) {
                $imageName = Carbon::now()->timestamp . '.' . $request->image->extension();
                $request->image->storeAs('img', $imageName);
                $customer->image = $imageName;
            }
            $customer->save();

            $notificationData = [
                "title" => "New customer register (" . ($customer->name ?? $customer->phone ?? '') . ") - " . formatDateWithTime($customer->created_at),
                "type" => "user_create",
                "user_type" => "admin",
                "store_id" => $customer->store_id,
            ];

            if (isset($notificationData['title']) && !empty($notificationData['title'])) {
                createNotification($notificationData);
            }


            // $address= new Address;
            // $address->uid=$customer->id;
            // $address->country=$request->country;
            // $address->state=$request->state;
            // $address->proper=$request->proper;
            // $address->save();
            $activity = " Save Customer " . $customer->id;
            $this->saveactivity($activity);
            Session::flash('message', 'Successfully created!');
            return redirect()->route('admin.customer');
        }
    }

    public function edit($id)
    {
        $cstmr = $this->checkrole();
        if (isset($cstmr) && $cstmr == "1" || Auth::user()->type == 'admin' || Auth::user()->type == 'dropshipper') {
            $urls = "customer";
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
            $toptool = Toptool::where('name', 'Customer')->where('uid', $user)->where('store_id', $store_id)->first();
            if (isset($toptool)) {
                $toptool->count = $toptool->count + 1;
                $toptool->save();
            } else {
                $toptool = new Toptool();
                $toptool->name = "Customer";
                $toptool->image = "rating.png";
                $toptool->url = "/customer";
                $toptool->count = "1";
                $toptool->uid = $user;
                $toptool->store_id = $store_id;
                $toptool->customer_id = $customer_id;
                $toptool->creator = $user;
                $toptool->editor = $user;
                $toptool->save();
            }
            $singleData = User::find($id);
            $singleDatass = Address::where('uid', $singleData->id)->first();
            $orderHistory = Order::selectRaw('SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) AS pending', ['pending'])
                ->selectRaw('SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) AS processing', ['processing'])
                ->selectRaw('SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) AS payment_failed', ['payment_failed'])
                ->selectRaw('SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) AS on_hold', ['on_hold'])
                ->selectRaw('SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) AS delivered', ['delivered'])
                ->selectRaw('SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) AS shipping', ['shipping'])
                ->selectRaw('SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) AS returned', ['returned'])
                ->selectRaw('SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) AS cancelled', ['cancelled'])
                ->where('store_id', $store_id)
                ->where('uid', '=', $singleData->id)
                ->first();
            $activity = " Edit Customer " . $singleDatass;
            $this->saveactivity($activity);
            // return view('admin.customer.edit')
            //     ->with('singleData', $singleData)
            //     ->with('singleDatass', $singleDatass)->with('urls', $urls);
            return view('admin.customer.edit')->with('singleData', $singleData)->with('singleDatass', $singleDatass)->with('urls', $urls)->with('orderHistory', $orderHistory);
        }
    }


    public function blockStatusChange($id)
    {
        if (isset($id) && !empty($id)) {
            $userData = getUserData();
            $store_id = $userData['store_id'];
            $blockUser = BlockUser::where('store_id', $store_id)->where('user_id', $id)->first();

            if (isset($blockUser)) {
                $blockUser->status = $blockUser->status == 1 ? 0 : 1;
                $blockUser->update();
            } else {
                $blockUser = new BlockUser();
                $blockUser->user_id = $id;
                $blockUser->store_id = $store_id;
                $blockUser->status = 1;
                $blockUser->save();
            }

            return response()->json(["status" => true, "message" => "Status change successfully!"]);
        }

        return response()->json(["status" => false, "message" => "Data not found!"]);
    }


    public function update(Request $request, $id)
    {
        $rules = array(
            'name' => 'required',
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            Session::flash('message', 'Name and Title Required');
            return redirect()->back()
                ->withErrors($validator);
        } else {

            $usr = User::find($id);
            $usr->name = $request->name;
            $usr->email = $request->email;
            $usr->phone = $request->phone;
            $usr->password = Hash::make($request->password);
            $usr->type = 'customer';
            $usr->address = $request->address;
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
            // $usr->uid=$user;

            $usr->customer_id = $customer_id;
            $usr->store_id = $store_id;
            // $usr->creator=$user;
            // $usr->editor=$user;

            if ($request->image) {
                $imageName = Carbon::now()->timestamp . '.' . $request->image->extension();
                $request->image->storeAs('img', $imageName);
                $usr->image = $imageName;
            }
            $usr->save();
            // dd("ok");
            $activity = " Update Customer " . $usr->id;
            $this->saveactivity($activity);
            Session::flash('message', 'Successfully Updated!');
            return redirect()->route('admin.customer');
        }
    }

    public function destroy($id)
    {
        $cstmr = $this->checkrole();
        if (isset($cstmr) && $cstmr == "1" || Auth::user()->type == 'admin' || Auth::user()->type == 'dropshipper') {
            $user = User::find($id);
            $user->delete();
            $activity = " Delete Customer " . $id;
            $this->saveactivity($activity);
            Session::flash('success_message', 'Successfully Deleted!');
            return redirect('customer');
        }
    }

    public function destroyNewsLetter($id)
    {
        $cstmr = $this->checkrole();
        if (isset($cstmr) && $cstmr == "1" || Auth::user()->type == 'admin' || Auth::user()->type == 'dropshipper') {
            $user = NewsLetter::find($id);
            $user->delete();
            $activity = " Delete Customer News Letter " . $id;
            $this->saveactivity($activity);
            Session::flash('success_message', 'Successfully Deleted!');
            return back();
        }
    }


    public function changecustomerssstatus(Request $request)
    {
        if ($request->text2 == '') {
            Session::flash('message', 'Please Select at least one items');
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
                    $user = User::where('id', $ids)->delete();
                }
            }
            $activity = " Delete Customer " . $request->text2;
            $this->saveactivity($activity);
            Session::flash('message', 'Successfully Deleted Customer');
            return back();
        }
    }


    public function newsLatterDelete(Request $request)
    {
        if ($request->text2 == '') {
            Session::flash('message', 'Please Select at least one items');
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
                    NewsLetter::where('id', $ids)->delete();
                }
            }
            // $activity = " Delete Customer " . $id;
            // $this->saveactivity($activity);
            Session::flash('message', 'Successfully Deleted Customer');
            return back();
        }
    }

}
