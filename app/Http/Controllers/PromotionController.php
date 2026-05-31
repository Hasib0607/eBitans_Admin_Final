<?php

namespace App\Http\Controllers;

use App\Http\Traits\ActivityLogTraits;
use App\Models\Campaign;
use App\Models\Coupon;
use App\Models\Headersetting;
use App\Models\Offer;
use App\Models\Product;
use App\Models\Store;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Session;
use Validator;
use Illuminate\Validation\Rule;

class PromotionController extends Controller
{
    use ActivityLogTraits;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function coupon()
    {
        if (canAccess('coupon')) {
            $urls = "promotion";

            /*extract user_id, user_type, store_id,customer, customer_id*/
            extract(getUserData());

            /*increment tools count*/
            topToolsCount('Coupon', "voucher.png", "/promotions/coupon");
            $currentCurrency = currentCurrency()['current_currency'];
            $activity = " Access Coupon Page";
            $this->saveactivity($activity);
            $coupons = Coupon::convertCurrency($store_id)->get();
            $setting = Headersetting::convertCurrency($store_id)->first();

            return view('admin.promotion.allcoupon')
                ->with('currentCurrency', $currentCurrency)
                ->with('coupons', $coupons)
                ->with('setting', $setting)
                ->with('store_id', $store_id)
                ->with('urls', $urls);
        }
    }

    public function changecouponstatus(Request $request)
    {
        /*extract user_id, user_type, store_id,customer, customer_id*/
        extract(getUserData());

        $id = $request->id;
        $value = $request->value;
        $product = Coupon::convertCurrency($store_id)->where('coupons.id', $id)->first();
        if (empty($product)) {
            return back();
        }
        if (isset($product) && $product->status == 'active') {
            $product->status = 'inactive';
        } else {
            $product->status = "active";
        }
        $product->save();
        $data = $product;
        $activity = " Change Coupon Status " . $product->code;
        $this->saveactivity($activity);
        return response()->json($data);
    }

    public function couponsave(Request $request)
    {
        /*extract user_id, user_type, store_id,customer, customer_id*/
        extract(getUserData());

        $rules = array(
            'name' => 'required',
            'code' => [
                'required',
                Rule::unique('coupons')->where(function ($query) use ($store_id) {
                    return $query->where('store_id', $store_id);
                }),
            ],
            'start_date' => 'required',
            'end_date' => 'required',
            'min_purchase' => 'required',
            'max_use' => 'required',
            'discount_type' => 'required',
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator);
        } else {
            if ($request->discount_type != "delivery_charge") {
                if (is_null($request->discount_amount) || empty($request->discount_amount)) {
                    $validator->errors()->add('discount_amount', 'Discount amount is required');
                    return redirect()->back()->withErrors($validator);
                }
            }

            $shipping_area = $request->shipping_area ?? NULL;
            if (!isset($request->shipping_area) || empty($request->shipping_area) || is_null($request->shipping_area)) {
                $shipping_area = NULL;
            }
            $payment_method = $request->payment_method ?? NULL;
            if (!isset($request->payment_method) || empty($request->payment_method) || is_null($request->payment_method)) {
                $payment_method = NULL;
            }

            if ($request->auto_apply == "on") {
                $auto_apply = 1;
            } else {
                $auto_apply = 0;
            }

            $coupon = new Coupon;
            $coupon->name = $request->name;
            $coupon->code = $request->code;
            $coupon->start_date = $request->start_date;
            $coupon->end_date = $request->end_date;
            $coupon->min_purchase = $request->min_purchase;
            $coupon->max_purchase = $request->max_purchase;
            $coupon->max_use = $request->max_use;
            $coupon->shipping_area = $shipping_area;
            $coupon->payment_method = $payment_method;
            $coupon->auto_apply = $auto_apply ?? 0;
            $coupon->discount_type = $request->discount_type;
            $coupon->discount_amount = $request->discount_type != "delivery_charge" ? $request->discount_amount : 0;
            if ($request->status == "on") {
                $coupon->status = "active";
            } else {
                $coupon->status = "inactive";
            }

            $currency_id = Store::findOrFail($store_id)['currency'];
            $coupon->currency_id = $currency_id;
            $coupon->uid = $user_id;
            $coupon->customer_id = $customer_id;
            $coupon->store_id = $store_id;
            $coupon->creator = $user_id;
            $coupon->editor = $user_id;
            $coupon->save();
            $activity = " Save Coupon " . $coupon->code;
            $this->saveactivity($activity);
            Session::flash('success_message', 'Coupon Save Successfully !');
            return redirect()->route('admin.promotion.coupon');
        }
    }

    public function couponexport(Request $request)
    {
        $date = Carbon::now();

        /*extract user_id, user_type, store_id,customer, customer_id*/
        extract(getUserData());

        $fileName = 'coupon(' . $date . ').csv';
        $coupon = Coupon::convertCurrency($store_id)->get();

        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );

        $columns = array(
            'Name',
            'Code',
            'Start Date',
            'End Date',
            'Discount Type',
            'Discount Amount',
            'Minimum Purchase',
            'Maximum Purchase',
            'Maximum Use',
            'Created_at'
        );

        $callback = function () use ($coupon, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($coupon as $cat) {
                $row['Name'] = $cat->name;
                $row['Code'] = $cat->code;
                $row['Start Date'] = $cat->start_date;
                $row['End Date'] = $cat->end_date;
                $row['Discount Type'] = $cat->discount_type;
                $row['Discount Amount'] = $cat->discount_amount;
                $row['Minimum Purchase'] = $cat->min_purchase;
                $row['Maximum Purchase'] = $cat->max_purchase;
                $row['Maximum Use'] = $cat->max_use;
                $row['Create Date'] = $cat->created_at;

                fputcsv($file, array(
                    $row['Name'],
                    $row['Code'],
                    $row['Start Date'],
                    $row['End Date'],
                    $row['Discount Type'],
                    $row['Discount Amount'],
                    $row['Minimum Purchase'],
                    $row['Maximum Purchase'],
                    $row['Maximum Use'],
                    $row['Create Date']
                ));
            }

            fclose($file);
        };
        $activity = " Export Coupon";
        $this->saveactivity($activity);
        return response()->stream($callback, 200, $headers);
    }

    public function editcoupon($id)
    {
        if (canAccess('coupon')) {
            $urls = "promotion";
            /*extract user_id, user_type, store_id,customer, customer_id*/
            extract(getUserData());

            /*increment tools count*/
            topToolsCount('Coupon', "voucher.png", "/promotions/coupon");

            $coupon = Coupon::convertCurrency($store_id)->where('coupons.id', $id)->first();
            if (empty($coupon)) {
                return back();
            }
            $activity = " Edit Coupon " . $coupon->code;
            $this->saveactivity($activity);
            $setting = Headersetting::convertCurrency($store_id)->first();

            return view('admin.promotion.editcoupon')
                ->with('coupon', $coupon)
                ->with('setting', $setting)
                ->with('store_id', $store_id)
                ->with('urls', $urls);
        }
    }

    public function updatecoupon(Request $request, $id)
    {
        /*extract user_id, user_type, store_id,customer, customer_id*/
        extract(getUserData());

        $rules = array(
            'name' => 'required',
            'code' => [
                'required',
                Rule::unique('coupons')
                    ->where(function ($query) use ($store_id) {
                        return $query->where('store_id', $store_id);
                    })
                    ->ignore($id), // ignore the current coupon ID
            ],
            'start_date' => 'required',
            'end_date' => 'required',
            'min_purchase' => 'required',
            'max_use' => 'required',
            'discount_type' => 'required',
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator);
        } else {
            if ($request->discount_type != "delivery_charge") {
                if (is_null($request->discount_amount) || empty($request->discount_amount)) {
                    $validator->errors()->add('discount_amount', 'Discount amount is required');
                    return redirect()->back()->withErrors($validator);
                }
            }

            $shipping_area = $request->shipping_area ?? NULL;
            if (!isset($request->shipping_area) || empty($request->shipping_area) || is_null($request->shipping_area)) {
                $shipping_area = NULL;
            }

            $payment_method = $request->payment_method ?? NULL;
            if (!isset($request->payment_method) || empty($request->payment_method) || is_null($request->payment_method)) {
                $payment_method = NULL;
            }

            if ($request->auto_apply == "on") {
                $auto_apply = 1;
            } else {
                $auto_apply = 0;
            }

            $coupon = Coupon::where('store_id', $store_id)->where('id', $id)->first();
            if (empty($coupon)) {
                return back();
            }
            $coupon->name = $request->name;
            $coupon->code = $request->code;
            $coupon->start_date = $request->start_date;
            $coupon->end_date = $request->end_date;
            $coupon->min_purchase = $request->min_purchase;
            $coupon->max_purchase = $request->max_purchase;
            $coupon->max_use = $request->max_use;
            $coupon->shipping_area = $shipping_area;
            $coupon->payment_method = $payment_method;
            $coupon->auto_apply = $auto_apply ?? 0;
            $coupon->discount_type = $request->discount_type;
            $coupon->discount_amount = $request->discount_type != "delivery_charge" ? $request->discount_amount : 0;
            if ($request->status == "on") {
                $coupon->status = "active";
            } else {
                $coupon->status = "inactive";
            }
            $currency_id = Store::findOrFail($store_id)['currency'];
            $coupon->currency_id = $currency_id;
            $coupon->editor = $user_id;
            $coupon->save();
            $activity = " Update Coupon " . $coupon->code;
            $this->saveactivity($activity);
            Session::flash('success_message', 'Coupon Update Successfully !');
            return redirect()->route('admin.promotion.coupon');
        }
    }

    public function deletecoupon($id)
    {
        if (canAccess('coupon')) {
            $store_id = getUserData()['store_id'];
            $coupon = Coupon::where('store_id', $store_id)->where('id', $id)->first();
            if (empty($coupon)) {
                return back();
            }

            $activity = " Delete Coupon " . $coupon->code;
            $this->saveactivity($activity);
            $coupon->delete();
            Session::flash('success_message', 'Coupon Delete Successfully !');
            return redirect()->route('admin.promotion.coupon');
        }
    }

    public function campaign()
    {
        if (canAccess('campaign')) {
            $urls = "promotion";

            /*extract user_id, user_type, store_id,customer, customer_id*/
            extract(getUserData());

            /*increment tools count*/
            topToolsCount(' Campaign', "bullhorn.png", "/promotions/campaign");
            $store = Store::with('current_currency')->find($store_id);
            $current_currency = $store->current_currency;

            $activity = " Access Campaign Page";
            $this->saveactivity($activity);
            $campaign = Campaign::convertCurrency($store_id)->get();

            return view('admin.promotion.campaign.index')
                ->with('campaigns', $campaign)
                ->with('store_id', $store_id)
                ->with('urls', $urls);
        }
    }

    public function changecampaignstatus(Request $request)
    {
        /*extract user_id, user_type, store_id,customer, customer_id*/
        extract(getUserData());

        $id = $request->id;
        $value = $request->value;
        $product = Campaign::where('store_id', $store_id)->where('id', $id)->first();
        if (empty($product)) {
            return back();
        }
        if (isset($product) && $product->status == 'active') {
            $product->status = 'inactive';
        } else {
            $product->status = "active";
        }
        $product->save();
        $data = $product;
        $activity = " Change Campaign Status " . $product->name;
        $this->saveactivity($activity);
        return response()->json($data);
    }

    public function addcampaign()
    {
        if (canAccess('campaign')) {
            $urls = "promotion";
            /*extract user_id, user_type, store_id,customer, customer_id*/
            extract(getUserData());

            /*increment tools count*/
            topToolsCount('Campaign', "bullhorn.png", "/promotions/campaign");

            $current_currency = currentCurrency()['current_currency'];

            $categories = DB::table('categories as c1')
                ->leftJoin('categories as c2', 'c1.parent', '=', 'c2.id')
                ->select('c1.*', 'c2.name as p_name')
                ->where('c1.store_id', $store_id)
                ->get();

            $products = Product::convertCurrency($store_id)
                ->get();

            $activity = " Access Add Campaign Page";
            $this->saveactivity($activity);

            $setting = Headersetting::convertCurrency($store_id)->first();

            return view('admin.promotion.campaign.create')
                ->with('products', $products)
                ->with('categories', $categories)
                ->with('urls', $urls)
                ->with('setting', $setting)
                ->with('current_currency', $current_currency)
                ->with('store_id', $store_id);
        }
    }

    public function storecampaign(Request $request)
    {
        $rules = array(
            'name' => 'required',
            'discount_type' => 'required',
            'discount_amount' => 'numeric|nullable'
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator);
        } else {
            $campaign = new Campaign;
            $campaign->name = $request->name;
            $campaign->length_type = $request->length;
            if (isset($request->start_date)) {
                $campaign->start_date = $request->start_date;
            }
            if (isset($request->end_date)) {
                $campaign->end_date = $request->end_date;
            }
            $campaign->specific_dates = $request->specific_date;
            // if(isset($request->repeat_date)){
            //     $campaign->repeat_dates=implode(',',$request->repeat_date);
            // }
            if ($request->time == '1') {
                $campaign->start_time = $request->start_time;
                $campaign->end_time = $request->end_time;
            }
            $campaign->discount_type = $request->discount_type;
            $campaign->discount_amount = $request->discount_type != "delivery_charge" ? $request->discount_amount : 0;
            $campaign->campaign_type = $request->campaign_type;

            $shipping_area = NULL;
            if ($request->discount_type == "delivery_charge") {
                $shipping_area = implode(',', $request->shipping_area ?? []);
            }
            $campaign->shipping_area = $shipping_area ?? NULL;

            if ($request->text2) {
                $campaign->products = $request->text2;
            }
            if ($request->text3) {
                $campaign->category = $request->text3;
            }
            if ($request->status == "on") {
                $campaign->status = "active";
            } else {
                $campaign->status = "inactive";
            }
            $currency_id = currentCurrency()['currency_id'];
            /*extract user_id, user_type, store_id,customer, customer_id*/
            extract(getUserData());

            $campaign->currency_id = $currency_id;
            $campaign->uid = $user_id;
            $campaign->store_id = $store_id;
            $campaign->customer_id = $customer_id;
            $campaign->creator = $user_id;
            $campaign->editor = $user_id;
            $campaign->save();
            $activity = " Save Campaign " . $campaign->name;
            $this->saveactivity($activity);
            Session::flash('success_message', 'campaign Add Successfully !');
            return redirect()->route('admin.promotion.campaign');
        }
    }

    public function campaignexport(Request $request)
    {
        $date = Carbon::now();

        /*extract user_id, user_type, store_id,customer, customer_id*/
        extract(getUserData());

        $fileName = 'campaign(' . $date . ').csv';
//            $coupon = Campaign::where('store_id', $store_id)->get();

        $coupon = Campaign::convertCurrency($store_id)
            ->get();

        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );

        $columns = array(
            'Name',
            'Start Date',
            'End Date',
            'Discount Type',
            'Discount Amount',
            'Type',
            'Products',
            'Campaign Type',
            'Category',
            'Length Type',
            'Specific Date',
            'Start Time',
            'End Time',
            'Created_at'
        );

        $callback = function () use ($coupon, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($coupon as $cat) {
                $row['Name'] = $cat->name;
                $row['Start Date'] = $cat->start_date;
                $row['End Date'] = $cat->end_date;
                $row['Discount Type'] = $cat->discount_type;
                $row['Discount Amount'] = $cat->discount_amount;
                $row['Type'] = $cat->code;
                $row['Products'] = $cat->products;
                $row['Campaign Type'] = $cat->campaign_type;
                $row['Category'] = $cat->category;
                $row['Length Type'] = $cat->length_type;
                $row['Specific Date'] = $cat->specific_dates;
                $row['Start Time'] = $cat->start_time;
                $row['End Time'] = $cat->end_time;
                $row['Create Date'] = $cat->created_at;

                fputcsv($file, array(
                    $row['Name'],
                    $row['Start Date'],
                    $row['End Date'],
                    $row['Discount Type'],
                    $row['Discount Amount'],
                    $row['Type'],
                    $row['Products'],
                    $row['Campaign Type'],
                    $row['Category'],
                    $row['Length Type'],
                    $row['Specific Date'],
                    $row['Start Time'],
                    $row['End Time'],
                    $row['Create Date']
                ));
            }

            fclose($file);
        };
        $activity = " Export Campaign";
        $this->saveactivity($activity);
        return response()->stream($callback, 200, $headers);
    }

    public function editcampaign($id)
    {
        if (canAccess('campaign')) {
            $urls = "promotion";

            /*extract user_id, user_type, store_id,customer, customer_id*/
            extract(getUserData());

            /*increment tools count*/
            topToolsCount(' Campaign', "bullhorn.png", "/promotions/campaign");

            /*get campaign using scope from Campaign Madel*/
            $campaign = Campaign::convertCurrency($store_id)
                ->firstWhere('campaigns.id', $id);
            if (empty($campaign)) {
                return back();
            }

            $campaign_products = Product::convertCurrency($store_id)
                ->whereIn('products.id', explode(',', $campaign->products))
                ->get();
            $products = Product::convertCurrency($store_id)
                ->get();

            $categories = DB::table('categories as c1')
                ->leftJoin('categories as c2', 'c1.parent', '=', 'c2.id')
                ->select('c1.*', 'c2.name as p_name')
                ->where('c1.store_id', $store_id)
                ->get();

            $campaign_categories = DB::table('categories as c1')
                ->leftJoin('categories as c2', 'c1.parent', '=', 'c2.id')
                ->select('c1.*', 'c2.name as p_name')
                ->whereIn('c1.id', explode(',', $campaign->category))
                ->get();

            $activity = " Edit Campaign " . $campaign->name;
            $this->saveactivity($activity);

            $setting = Headersetting::convertCurrency($store_id)->first();

            return view('admin.promotion.campaign.edit')
                ->with('campaign_categories', $campaign_categories)
                ->with('categories', $categories)
                ->with('products', $products)
                ->with('campaign_products', $campaign_products)
                ->with('campaign', $campaign)
                ->with('store_id', $store_id)
                ->with('setting', $setting)
                ->with('urls', $urls);
        }
    }

    public function productupdatecampaign(Request $request, $id)
    {
        /*extract user_id, user_type, store_id,customer, customer_id*/
        extract(getUserData());

        $campaign = Campaign::where('store_id', $store_id)->where('id', $id)->first();
        if (empty($campaign)) {
            return back();
        }

        if ($request->text2) {
            $c = $campaign->products;
            if (isset($c)) {
                $array = $c . ',' . $request->text2;
            } else {
                $array = $request->text2;
            }
            $arr = explode(',', $array);
            $arr2 = array_unique($arr);
            $arr1 = implode(',', $arr2);
            $campaign->products = $arr1;
        }
        $campaign->save();
        $activity = " Campaign Product Update " . $campaign->name;
        $this->saveactivity($activity);
        Session::flash('success_message', 'campaign Update Successfully !');
        return redirect()->route('admin.promotion.campaign');

    }

    public function categoryupdatecampaign(Request $request, $id)
    {
        /*extract user_id, user_type, store_id,customer, customer_id*/
        extract(getUserData());

        $campaign = Campaign::where('store_id', $store_id)->where('id', $id)->first();
        if (empty($campaign)) {
            return back();
        }
        if ($request->text3) {
            $c = $campaign->category;
            if (isset($c)) {
                $array = $c . ',' . $request->text3;
            } else {
                $array = $request->text3;
            }
            $arr = explode(',', $array);
            $arr2 = array_unique($arr);
            $arr1 = implode(',', $arr2);
            $campaign->category = $arr1;
        }
        $campaign->save();
        $activity = " Campaign Category Update " . $campaign->name;
        $this->saveactivity($activity);
        Session::flash('success_message', 'campaign Update Successfully !');
        return redirect()->route('admin.promotion.campaign');

    }

    public function updatecampaign(Request $request, $id)
    {
        /*extract user_id, user_type, store_id,customer, customer_id*/
        extract(getUserData());

        $rules = array(
            'name' => 'required',
            'discount_type' => 'required',
            'discount_amount' => 'numeric|nullable'
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator);
        } else {
            $campaign = Campaign::where('store_id', $store_id)->where('id', $id)->first();
            if (empty($campaign)) {
                return back();
            }
            $campaign->name = $request->name;
            $campaign->length_type = $request->length;
            if (isset($request->start_date)) {
                $campaign->start_date = $request->start_date;
            }
            if (isset($request->end_date)) {
                $campaign->end_date = $request->end_date;
            }
            $campaign->specific_dates = $request->specific_date;
            if ($request->time == '1') {
                $campaign->start_time = $request->start_time;
                $campaign->end_time = $request->end_time;
            } else {
                $campaign->start_time = null;
                $campaign->end_time = null;
            }
            $campaign->discount_type = $request->discount_type;
            $campaign->discount_amount = $request->discount_type != "delivery_charge" ? $request->discount_amount : 0;
            $campaign->campaign_type = $request->campaign_type;

            $shipping_area = NULL;
            if ($request->discount_type == "delivery_charge") {
                $shipping_area = implode(',', $request->shipping_area ?? []);
            }
            $campaign->shipping_area = $shipping_area ?? NULL;

            if ($request->text2) {
                $c = $campaign->products;
                if (isset($c)) {
                    $array = $c . ',' . $request->text2;
                } else {
                    $array = $request->text2;
                }
                $arr = explode(',', $array);
                $arr2 = array_unique($arr);
                $arr1 = implode(',', $arr2);
                $campaign->products = $arr1;
            }
            if ($request->text3) {
                $c = $campaign->category;
                if (isset($c)) {
                    $array = $c . ',' . $request->text3;
                } else {
                    $array = $request->text3;
                }
                $arr = explode(',', $array);
                $arr2 = array_unique($arr);
                $arr1 = implode(',', $arr2);
                $campaign->category = $arr1;
            }
            if ($request->campaign_type == 'product') {
                $campaign->category = null;
            } else {
                $campaign->products = null;
            }
            if ($request->status == "on") {
                $campaign->status = "active";
            } else {
                $campaign->status = "inactive";
            }
            $currency_id = Store::findOrFail($store_id)['currency'];
            $campaign->currency_id = $currency_id;
            $campaign->editor = $user_id;
            $campaign->save();
            $activity = " Update Campaign " . $campaign->name;
            $this->saveactivity($activity);
            Session::flash('success_message', 'campaign Update Successfully !');
            return redirect()->route('admin.promotion.campaign');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function rmvcmp($cid, $id)
    {
        /*extract user_id, user_type, store_id,customer, customer_id*/
        extract(getUserData());

        $cmp = Campaign::where('store_id', $store_id)->where('id', $cid)->first();
        if (empty($cmp)) {
            return back();
        }
        $p = explode(',', $cmp->products);
        $b = array_diff($p, [$id]);
        $c = implode(',', $b);
        $cmp->products = $c;
        $cmp->save();
        $activity = " Remove Product From Campaign " . $cmp->name;
        $this->saveactivity($activity);
        return back();
    }

    public function multipledeletecampro(Request $request)
    {
        $cmp = Campaign::find($request->campid);
        $p = explode(',', $cmp->products);
        $id = explode(',', $request->text31);
        $b = array_diff($p, $id);
        $c = implode(',', $b);
        $cmp->products = $c;
        $cmp->save();
        return back();
    }

    public function multipledeletecamcat(Request $request)
    {
        $cmp = Campaign::find($request->campid);
        $p = explode(',', $cmp->category);
        $id = explode(',', $request->text31);
        $b = array_diff($p, $id);
        $c = implode(',', $b);
        $cmp->category = $c;
        $cmp->save();
        return back();
    }

    public function rmvcmpcat($cid, $id)
    {
        /*extract user_id, user_type, store_id,customer, customer_id*/
        extract(getUserData());

        $cmp = Campaign::where('store_id', $store_id)->where('id', $cid)->first();
        if (empty($cmp)) {
            return back();
        }
        $p = explode(',', $cmp->category);
        $b = array_diff($p, [$id]);
        $c = implode(',', $b);
        $cmp->category = $c;
        $cmp->save();
        $activity = " Remove Category From Campaign " . $cmp->name;
        $this->saveactivity($activity);
        return back();
    }

    public function rmvcmppro($cid, $id)
    {
        /*extract user_id, user_type, store_id,customer, customer_id*/
        extract(getUserData());

        $cmp = Campaign::where('store_id', $store_id)->where('id', $cid)->first();
        if (empty($cmp)) {
            return back();
        }
        $p = explode(',', $cmp->products);
        $b = array_diff($p, [$id]);
        $c = implode(',', $b);
        $cmp->products = $c;
        $cmp->save();
        $activity = " Remove Product From Campaign " . $cmp->name;
        $this->saveactivity($activity);
        return back();
    }

    public function offer()
    {
        if (canAccess('offer')) {
            $urls = "promotion";

            /*extract user_id, user_type, store_id,customer, customer_id*/
            extract(getUserData());

            /*increment tools count*/
            topToolsCount(' Offer', "offer.png", "/promotion/offer");

            $offer = Offer::convertCurrency($store_id)
                ->first();

            $products = Product::convertCurrency($store_id)
                ->where('products.discount_type', '!=', 'no_discount')
                ->get();

            $activity = " Access Offer Page";
            $this->saveactivity($activity);
            return view('admin.promotion.offer.edit')
                ->with('products', $products)
                ->with('offer', $offer)
                ->with('store_id', $store_id)
                ->with('urls', $urls);
        }
    }

    public function storeoffer(Request $request)
    {
        $rules = array(
            'name' => 'required',
            'start_date' => 'required',
            'end_date' => 'required'
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator);
        } else {
            $offer = new Offer;
            $offer->name = $request->name;
            $offer->start_date = $request->start_date;
            $offer->end_date = $request->end_date;
            if ($request->text2) {
                $c = $offer->products;
                if ($c != '') {
                    $array = $c . ',' . $request->text2;
                } else {
                    $array = $request->text2;
                }

                $arr = explode(',', $array);
                $arr2 = array_unique($arr);
                $arr1 = implode(',', $arr2);
                $offer->products = $arr1;
            }
            if ($request->status == "on") {
                $offer->status = "active";
                $this->productDiscountTypeChange(true);
            } else {
                $offer->status = "inactive";
                $this->productDiscountTypeChange(false);
            }
            /*extract user_id, user_type, store_id,customer, customer_id*/
            extract(getUserData());
            $store = Store::findOrFail($store_id);
            $offer->currency_id = $store->currency;
            $offer->uid = $user_id;
            $offer->customer_id = $customer_id;
            $offer->store_id = $store_id;
            $offer->creator = $user_id;
            $offer->editor = $user_id;
            $offer->save();
            $activity = " Save Offer " . $offer->name;
            $this->saveactivity($activity);
            Session::flash('success_message', 'Offer Save Successfully !');
            return redirect()->route('admin.promotion.offer');
        }
    }

    public function productDiscountTypeChange($status)
    {
        $userData = getUserData();
        $store_id = $userData['store_id'];

        $products = Product::where('store_id', $store_id)->where('discount_type', "!=", "no_discount")->where(function ($q) {
            $q->where('discount_product', 0)->orWhereNull('prev_discount');
        })->get();

        if (count($products) > 0) {
            foreach ($products as $product) {
                if ($product->discount_type != "no_discount") {
                    $product->discount_product = 1;
                    $product->prev_discount = $product->discount_type;
                    $product->update();
                }
            }
        }

        $products = Product::where('store_id', $store_id)->where('discount_product', 1)->get();

        if ($status) {
            foreach ($products as $product) {
                $product->discount_type = $product->prev_discount ?? "no_discount";
                $product->update();
            }
        } else {
            foreach ($products as $product) {
                $product->prev_discount = $product->discount_type;
                $product->discount_type = "no_discount";
                $product->update();
            }
        }

    }

    public function updateoffer(Request $request, $id)
    {
        $rules = array(
            'name' => 'required',
            'start_date' => 'required',
            'end_date' => 'required'
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator);
        } else {
            $offer = Offer::find($id);
            $offer->name = $request->name;
            $offer->start_date = $request->start_date;
            $offer->end_date = $request->end_date;
            if ($request->text2) {
                $c = $offer->products;
                if ($c != '') {
                    $array = $c . ',' . $request->text2;
                } else {
                    $array = $request->text2;
                }

                $arr = explode(',', $array);
                $arr2 = array_unique($arr);
                $arr1 = implode(',', $arr2);
                $offer->products = $arr1;
            }

            if ($request->status == "on") {
                $offer->status = "active";
                $this->productDiscountTypeChange(true);
            } else {
                $offer->status = "inactive";
                $this->productDiscountTypeChange(false);
            }
            $offer->save();
            $activity = " Update Offer " . $offer->name;
            $this->saveactivity($activity);
            Session::flash('success_message', 'Offer Update Successfully !');
            return redirect()->route('admin.promotion.offer');
        }
    }

    public function rmvofr($cid, $id)
    {
        /*extract user_id, user_type, store_id,customer, customer_id*/
        extract(getUserData());

        $cmp = Offer::where('store_id', $store_id)->where('id', $cid)->first();
        if (empty($cmp)) {
            return back();
        }
        $p = explode(',', $cmp->products);
        $b = array_diff($p, [$id]);
        $c = implode(',', $b);
        $cmp->products = $c;
        $cmp->save();
        $activity = " Remove Product From Offer " . $cmp->name;
        $this->saveactivity($activity);
        return back();
    }

    public function deletecampaign($id)
    {
        /*extract user_id, user_type, store_id,customer, customer_id*/
        extract(getUserData());

        $cam = Campaign::where('store_id', $store_id)->where('id', $id)->first();
        if (empty($cam)) {
            return back();
        }
        $activity = " Delete Campaign " . $cam->name;
        $this->saveactivity($activity);
        $cam->delete();
        Session::flash('success_message', 'Campaign Delete Successfully !');
        return redirect()->route('admin.promotion.campaign');
    }

    public function changecouponsstatus(Request $request)
    {
        if ($request->text2 == '') {
            Session::flash('message', 'Please Select at least one item');
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
                    $product = Coupon::find($ids);
                    $product->status = 'active';
                    $product->save();
                }
            }
            $activity = " Change Coupon Status";
            $this->saveactivity($activity);
            Session::flash('message', 'Successfully Active Coupon');
            return back();
        }
        if ($request->action == 'deactive') {
            $id = explode(',', $request->text2);
            if (isset($id) && count($id) > 0) {
                foreach ($id as $ids) {
                    $product = Coupon::find($ids);
                    $product->status = 'deactive';
                    $product->save();
                }
            }
            $activity = " Change Coupon Status";
            $this->saveactivity($activity);
            Session::flash('message', 'Successfully Deactive Coupon');
            return back();
        }
        if ($request->action == 'delete') {
            $id = explode(',', $request->text2);
            if (isset($id) && count($id) > 0) {
                foreach ($id as $ids) {
                    $product = Coupon::find($ids);
                    $product->delete();
                }
            }
            $activity = " Delete Coupon";
            $this->saveactivity($activity);
            Session::flash('message', 'Successfully Deleted Coupon');
            return back();
        }
    }

    public function changecampaignssstatus(Request $request)
    {
        if ($request->text2 == '') {
            Session::flash('message', 'Please Select Campaign');
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
                    $product = Campaign::find($ids);
                    $product->status = 'active';
                    $product->save();
                }
            }
            $activity = " Change Campaign Status";
            $this->saveactivity($activity);
            Session::flash('message', 'Successfully Active Campaign');
            return back();
        }
        if ($request->action == 'deactive') {
            $id = explode(',', $request->text2);
            if (isset($id) && count($id) > 0) {
                foreach ($id as $ids) {
                    $product = Campaign::find($ids);
                    $product->status = 'deactive';
                    $product->save();
                }
            }
            $activity = " Change Campaign Status";
            $this->saveactivity($activity);
            Session::flash('message', 'Successfully Deactive Campaign');
            return back();
        }
        if ($request->action == 'delete') {
            $id = explode(',', $request->text2);
            if (isset($id) && count($id) > 0) {
                foreach ($id as $ids) {
                    $product = Campaign::where('id', $ids)->delete();
                }
            }
            $activity = " Delete Campaign";
            $this->saveactivity($activity);
            Session::flash('message', 'Successfully Deleted Campaign');
            return back();
        }
    }

    public function removefromofrsss($id)
    {
        /*extract user_id, user_type, store_id,customer, customer_id*/
        extract(getUserData());

        $product = Product::where('store_id', $store_id)->where('id', $id)->first();
        if (empty($product)) {
            return back();
        }
        $product->discount_type = "no_discount";
        $product->save();
        return back();
    }

    public function offerprodelete(Request $request)
    {
        /*extract user_id, user_type, store_id,customer, customer_id*/
        extract(getUserData());


        $ids = explode(',', $request->text2);
        if (isset($ids) && count($ids) > 0) {
            foreach ($ids as $id) {
                $product = Product::where('store_id', $store_id)->where('id', $id)->first();
                if (empty($product)) {
                    return back();
                }
                if (isset($product)) {
                    $product->discount_type = "no_discount";
                    $product->save();
                }
            }
        }
        return back();
    }
}
