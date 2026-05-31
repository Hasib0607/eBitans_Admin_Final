<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Orderitem;
use App\Models\Order;
use App\Models\Content;
use App\Models\Customer;
use App\Models\Staff;
use App\Models\Campaign;
use App\Models\Product;
use App\Models\Category;
use App\Models\Branchproduct;
use App\Models\Boosting;
use App\Models\Digitalcontent;
use App\Models\RequiredInformation;
use App\Models\RequiredInformationForContent;
use App\Models\Store;
use Carbon\Carbon;
use App\Models\Toptool;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;

class DigitalMarketingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $urls = "digital";
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
        $store = Store::find($store_id);
        $digitalplan = $store->digitalplan;

        // dd($digitalplan);

        $startDate = $store->digital_plan_start_date . " 00:00:00";
        $endDate = $store->digital_plan_expiry_date . " 00:00:00";

        $sc = Content::where('store_id', $store_id)->where('type', 'Static Content')->where('created_at', '>=', $startDate)->where('created_at', '>=', $endDate)->count();
        $vc = Content::where('store_id', $store_id)->where('type', 'Video Content')->where('created_at', '>=', $startDate)->where('created_at', '>=', $endDate)->count();
        $gc = Content::where('store_id', $store_id)->where('type', 'Gify Content')->where('created_at', '>=', $startDate)->where('created_at', '>=', $endDate)->count();
        $cw = Content::where('store_id', $store_id)->where('type', 'Caption Writting')->where('created_at', '>=', $startDate)->where('created_at', '>=', $endDate)->count();


        $downloadContent = Content::where('store_id', $store_id)->take(5)->orderBy('id', 'DESC')->get();

        return view('admin.digital.index', compact('urls', 'digitalplan', 'store', 'sc', 'vc', 'gc', 'cw', 'downloadContent'));
    }

    public function content_download()
    {
        $urls = "digital";
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
        $sc = Content::where('store_id', $store_id)->where('type', 'Static Content')->get();
        $vc = Content::where('store_id', $store_id)->where('type', 'Video Content')->get();
        $gc = Content::where('store_id', $store_id)->where('type', 'Gify Content')->get();
        $cw = Content::where('store_id', $store_id)->where('type', 'Caption Writting')->get();
        return view('admin.digital.content_download', compact('urls', 'sc', 'vc', 'gc', 'cw'));
    }

    public function contentFileDownload(Request $request)
    {
        $filepath = public_path($request->pathName);

        return Response::download($filepath);
    }

    public function contentFileView($id)
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

        $products = Content::where('id', $id)->where('store_id', $store_id)->first();


        return view('admin.digital.content_view', compact('products'));
    }


    public function content_correction()
    {
        $urls = "digital";
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
        $token = $store_id;
        $digitalcontent = Digitalcontent::where('store_id', $store_id)->first();
        if (isset($digitalcontent)) {
            $token = $digitalcontent->token;
        } else {
            $dct = new Digitalcontent();
            $dct->uid = $user;
            $dct->customer_id = $customer->id;
            $dct->store_id = $store_id;
            $token = "CC" . sixDigitRandCode();
            $dct->token = $token;
            $dct->save();
        }
        return view('admin.digital.content_correction', compact('urls', 'token'));
    }

    public function boosting()
    {
        $urls = "digital";
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
        $boostings = Boosting::where('store_id', $store_id)->get();
        return view('admin.digital.boosting', compact('urls', 'boostings'));
    }

    public function submitboosting(Request $request)
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
        $boost = new Boosting();
        $boost->type = $request->type;
        $boost->amount = $request->amount;
        $boost->from = $request->from;
        $boost->to = $request->to;
        $boost->status = "Pending";
        $boost->note = $request->note;
        $boost->content = $request->content ?? null;
        $boost->uid = $user;
        $boost->store_id = $store_id;
        $boost->customer_id = $customer_id;
        $boost->save();
        Session::flash('message', 'Successfully save Boosting Request');
        return back();
    }

    public function downloadcontent($id)
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
        $fileName = 'content(' . $date . ').txt';
        $products = Content::where('id', $id)->where('store_id', $store_id)->get();

        $headers = array(
            "Content-type" => "text/txt",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );

        $columns = array('details');

        $callback = function () use ($products, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($products as $product) {
                $row['id'] = $product->id;
                $row['name'] = $product->name;
                $row['store'] = $product->store->name;
                $row['type'] = $product->type;
                $row['details'] = $product->details;

                fputcsv($file, array($row['details']));
            }

            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }


    public function requiredInformation()
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

        $data['required_information'] = RequiredInformation::where('client_id', Auth::user()->id)->where('store_id', $store_id)->first();
        $data['required_information_contents'] = RequiredInformationForContent::where('client_id', Auth::user()->id)->where('store_id', $store_id)->orderBy('id', 'DESC')->get();

        return view('admin.digital.required_information', $data);
    }
}
