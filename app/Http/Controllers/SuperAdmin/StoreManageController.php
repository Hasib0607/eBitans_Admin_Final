<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\FileManager\CustomConfigHandler;
use App\Models\Activity;
use App\Models\Activitylog;
use App\Models\Addon;
use App\Models\AdminBlog;
use App\Models\AdminBlogType;
use App\Models\AdminChatSupport;
use App\Models\AdminNotification;
use App\Models\AdminUserAnalytics;
use App\Models\AdminVisitor;
use App\Models\Announcement;
use App\Models\Banner;
use App\Models\BkashIDToken;
use App\Models\BlockUser;
use App\Models\BlogCoverImage;
use App\Models\Booking;
use App\Models\BookingCustomerFiled;
use App\Models\Boosting;
use App\Models\Branch;
use App\Models\Branchproduct;
use App\Models\Brand;
use App\Models\BuyModulus;
use App\Models\Campaign;
use App\Models\Cart;
use App\Models\Category;
use App\Models\ChatConversation;
use App\Models\ChatMessage;
use App\Models\CheckoutForm;
use App\Models\ClientActivitieComments;
use App\Models\Color;
use App\Models\Content;
use App\Models\Coupon;
use App\Models\Courier;
use App\Models\CourierDelivery;
use App\Models\Customer;
use App\Models\Design;
use App\Models\DesignPosition;
use App\Models\Digitalcontent;
use App\Models\Domain;
use App\Models\ExpoDeviceToken;
use App\Models\Headersetting;
use App\Models\HomePae;
use App\Models\Invoice;
use App\Models\Invoicepurchase;
use App\Models\MarchantPaymentGetwayKYC;
use App\Models\Menu;
use App\Models\Message;
use App\Models\Mobileapp;
use App\Models\NewsLetter;
use App\Models\Notification;
use App\Models\Offer;
use App\Models\Order;
use App\Models\Orderitem;
use App\Models\Page;
use App\Models\Paymentgateway;
use App\Models\Planorder;
use App\Models\Prereguser;
use App\Models\Product;
use App\Models\ProductAffiliateCommission;
use App\Models\ProductAffiliateInfo;
use App\Models\ProductLayout;
use App\Models\ProductTransfer;
use App\Models\Pse\PseVisitorCounter;
use App\Models\QuickLogin;
use App\Models\Referral;
use App\Models\RequiredInformation;
use App\Models\RequiredInformationForContent;
use App\Models\Review;
use App\Models\Role;
use App\Models\Size;
use App\Models\Slider;
use App\Models\SMSLogger;
use App\Models\Staff;
use App\Models\Store;
use App\Models\StoreDesign;
use App\Models\Supplier;
use App\Models\TempImage;
use App\Models\Testimonial;
use App\Models\Themecustomize;
use App\Models\Toptool;
use App\Models\Unit;
use App\Models\User;
use App\Models\Veriant;
use App\Models\Webmail;
use App\Models\Websitesetup;
use App\Models\Work;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class StoreManageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()->type == 'superadmin') {
            $urls = "store_manege";
            $allStore = Store::get();

            return view('superadmin.store_manage.index', compact('allStore', 'urls'));
        }
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function storeDeleteAuthCheck(Request $request)
    {
        if (Hash::check($request->id, Auth::user()->password)) {
            return response()->json(['status' => true]);
        } else {
            return response()->json(['status' => false]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function storeDelete(Request $request)
    {
        $id = $request->id;
        if (Auth::user()->type == 'superadmin') {
            Toptool::where('store_id', $id)->delete();
            Design::where('store_id', $id)->delete();
            Domain::where('store_id', $id)->delete();
            Activity::where('store_id', $id)->delete();
            Activitylog::where('store_id', $id)->delete();
            Addon::where('store_id', $id)->delete();
            Boosting::where('store_id', $id)->delete();
            Campaign::where('store_id', $id)->delete();
            Coupon::where('store_id', $id)->delete();
            Color::where('store_id', $id)->delete();
            Content::where('store_id', $id)->delete();
            Digitalcontent::where('store_id', $id)->delete();
            Invoicepurchase::where('store_id', $id)->delete();
            Invoice::where('store_id', $id)->delete();
            Menu::where('store_id', $id)->delete();
            Message::where('store_id', $id)->delete();
            Mobileapp::where('store_id', $id)->delete();
            Offer::where('store_id', $id)->delete();
            Page::where('store_id', $id)->delete();
            Paymentgateway::where('store_id', $id)->delete();
            Planorder::where('store_id', $id)->delete();
            Prereguser::where('store_id', $id)->delete();
            RequiredInformation::where('store_id', $id)->delete();
            RequiredInformationForContent::where('store_id', $id)->delete();
            Review::where('store_id', $id)->delete();
            Role::where('store_id', $id)->delete();
            Size::where('store_id', $id)->delete();
            Supplier::where('store_id', $id)->delete();
            Themecustomize::where('store_id', $id)->delete();
            Unit::where('store_id', $id)->delete();
            Webmail::where('store_id', $id)->delete();
            Websitesetup::where('store_id', $id)->delete();
            Work::where('store_id', $id)->delete();
            SMSLogger::where('store_id', $id)->delete();
            StoreDesign::where('store_id', $id)->delete();
            Referral::where('store_id', $id)->delete();
            QuickLogin::where('store_id', $id)->delete();
            PseVisitorCounter::where('store_id', $id)->delete();
            ProductTransfer::where('store_id', $id)->delete();
            ProductLayout::where('store_id', $id)->delete();
            ProductAffiliateInfo::where('store_id', $id)->delete();
            ProductAffiliateCommission::where('store_id', $id)->delete();
            Notification::where('store_id', $id)->delete();
            NewsLetter::where('store_id', $id)->delete();
            NewsLetter::where('store_id', $id)->delete();
            MarchantPaymentGetwayKYC::where('store_id', $id)->delete();
            HomePae::where('store_id', $id)->delete();
            ExpoDeviceToken::where('store_id', $id)->delete();
            DesignPosition::where('store_id', $id)->delete();
            CourierDelivery::where('store_id', $id)->delete();
            Courier::where('store_id', $id)->delete();
            ClientActivitieComments::where('store_id', $id)->delete();
            CheckoutForm::where('store_id', $id)->delete();
            Cart::where('store_id', $id)->delete();
            BuyModulus::where('store_id', $id)->delete();
            BookingCustomerFiled::where('store_id', $id)->delete();
            Booking::where('store_id', $id)->delete();
            BlockUser::where('store_id', $id)->delete();
            BkashIDToken::where('store_id', $id)->delete();
            Announcement::where('store_id', $id)->delete();
            AdminVisitor::where('store_id', $id)->delete();
            AdminUserAnalytics::where('store_id', $id)->delete();
            AdminNotification::where('store_id', $id)->delete();
            AdminChatSupport::where('store_id', $id)->delete();
            AdminBlogType::where('store_id', $id)->delete();

            $branchIds = Branch::where('store_id', $id)->pluck('id');
            Branchproduct::whereIn('branch_id', $branchIds)->delete();
            Branch::where('store_id', $id)->delete();


            deleteTableDataHelper(AdminBlog::class, $id, [
                'image' => public_path('BlogImages') . "/",
                'thumbnail' => public_path('BlogImages') . "/"
            ]);

            deleteTableDataHelper(BlogCoverImage::class, $id, [
                'image' => 'assets/images/banner/'
            ]);

            deleteTableDataHelper(BlogCoverImage::class, $id, [
                'image' => 'BlogImages/'
            ]);

            deleteTableDataHelper(Brand::class, $id, [
                'image' => 'assets/images/brand/'
            ]);

            deleteTableDataHelper(Category::class, $id, [
                'image' => 'assets/images/category/'
            ]);


            $conversationIds = ChatConversation::where('store_id', $id)->pluck('id');
            ChatMessage::whereIn('conversation_id', $conversationIds)->delete();
            ChatConversation::where('store_id', $id)->delete();


            deleteTableDataHelper(Headersetting::class, $id, [
                'logo' => "assets/images/setting/",
                'favicon' => "assets/images/setting/favicon/"
            ]);

            $orderIds = Order::where('store_id', $id)->pluck('id');
            Orderitem::whereIn('order_id', $orderIds)->delete();
            Order::where('store_id', $id)->delete();


            deleteTableDataHelper(Slider::class, $id, [
                'image' => "assets/images/slider/",
                'subimage' => "assets/images/slider/"
            ]);


            $ProductIds = Product::where('store_id', $id)->pluck('id');
            $veriants = Veriant::whereIn('pid', $ProductIds)->get();

            if ($veriants->isNotEmpty()) {
                $columnsWithPaths = [
                    'image' => "assets/images/product/",
                    'color_image' => "assets/images/product/"
                ];
                foreach ($veriants as $item) {
                    foreach ($columnsWithPaths as $column => $path) {
                        $image = $item->$column ?? null;
                        $fullPath = rtrim($path, '/') . '/' . ltrim($image, '/');

                        if ($image && !demoImageCheck($image)) {
                            deleteFile($fullPath);
                        }
                    }
                    $item->delete();
                }
            }

            deleteTableDataHelper(Product::class, $id, [
                'images' => "assets/images/product/"
            ]);


            $staffsId = Staff::where('store_id', $id)->pluck('uid');
            $users = User::whereIn('id', $staffsId)->get();
            if (isset($users) && count($users) > 0) {
                foreach ($users as $user) {
                    $user->delete();
                }
            }
            Staff::where('store_id', $id)->delete();


            deleteTableDataHelper(TempImage::class, $id, [
                'image' => "pageImages/"
            ]);

            deleteTableDataHelper(Testimonial::class, $id, [
                'image' => "assets/images/testimonials/"
            ]);


            $cust = Customer::where('active_store', $id)->first();
            if ($cust) {
                $cust->active_store = 0;
            }

            $store = Store::where('id', $id)->first();
            if (isset($store)) {
                // Path inside "storage/app/public"
                $folder = 'photos/' . CustomConfigHandler::getStoreFolderName($store);

                // Delete folder
                if (Storage::disk('public')->exists($folder)) {
                    Storage::disk('public')->deleteDirectory($folder);
                }

                $store->delete();
            }

            Session::flash('message', 'Store Deleted Successfully!');
            return back()->with('success_message', 'Store Deleted Successfully!');
        }

        Session::flash('message', 'Your not Authorized for Delete!');
        return back()->with('success_message', 'Your not Authorized for Delete!');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function storeDeleteMulti(Request $request)
    {
        $storeId = explode(",", $request->text2);
        if (Auth::user()->type == 'superadmin') {
            foreach ($storeId as $key => $id) {
                Toptool::where('store_id', $id)->delete();
                Testimonial::where('store_id', $id)->delete();
                Banner::where('store_id', $id)->delete();
                Slider::where('store_id', $id)->delete();
                Headersetting::where('store_id', $id)->delete();
                Design::where('store_id', $id)->delete();
                Category::where('store_id', $id)->delete();
                Product::where('store_id', $id)->delete();
                Domain::where('store_id', $id)->delete();
                Activity::where('store_id', $id)->delete();
                Activitylog::where('store_id', $id)->delete();
                Addon::where('store_id', $id)->delete();
                Boosting::where('store_id', $id)->delete();
                Branch::where('store_id', $id)->delete();
                Brand::where('store_id', $id)->delete();
                Campaign::where('store_id', $id)->delete();
                Coupon::where('store_id', $id)->delete();
                Color::where('store_id', $id)->delete();
                Content::where('store_id', $id)->delete();
                Digitalcontent::where('store_id', $id)->delete();
//                HomePae::where('store_id', $id)->delete();
                Invoicepurchase::where('store_id', $id)->delete();
                Invoice::where('store_id', $id)->delete();
                Menu::where('store_id', $id)->delete();
                Message::where('store_id', $id)->delete();
                Mobileapp::where('store_id', $id)->delete();
                Offer::where('store_id', $id)->delete();
                Order::where('store_id', $id)->delete();
                Page::where('store_id', $id)->delete();
                Paymentgateway::where('store_id', $id)->delete();
                Planorder::where('store_id', $id)->delete();
                Prereguser::where('store_id', $id)->delete();
                RequiredInformation::where('store_id', $id)->delete();
                RequiredInformationForContent::where('store_id', $id)->delete();
                Review::where('store_id', $id)->delete();
                Role::where('store_id', $id)->delete();
                Size::where('store_id', $id)->delete();
                Staff::where('store_id', $id)->delete();
                Supplier::where('store_id', $id)->delete();
                Themecustomize::where('store_id', $id)->delete();
                Unit::where('store_id', $id)->delete();
                Webmail::where('store_id', $id)->delete();
                Websitesetup::where('store_id', $id)->delete();

                $cust = Customer::where('active_store', $id)->first();
                if ($cust) {
                    $cust->active_store = 0;
                }

                Store::where('id', $id)->delete();
            }

            Session::flash('message', 'Store Deleted Successfully!');
            return back()->with('success_message', 'Store Deleted Successfully!');
        }

        Session::flash('message', 'Your not Authorized for Delete!');
        return back()->with('success_message', 'Your not Authorized for Delete!');
    }


    public function storeStatus(Request $request)
    {
        $value = $request->value;
        $store_id = $request->id;

        if (empty($store_id)) {
            return response()->json([
                'status' => false,
                'message' => 'ID not found.'
            ]);
        }

        if ($value != 'on') {
            return response()->json([
                'status' => false,
                'message' => 'Value Not Found.'
            ]);
        }

        $store = Store::where('id', $store_id)->first();

        if (!is_null($store)) {
            if ($store->status == "active") {
                $store->status = "deactive";
            } elseif ($store->status == "deactive") {
                $store->status = "active";
            }

            $store->save();

            return response()->json([
                'status' => true,
                'message' => 'Store status changed successfully !'
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'Store not found.'
        ]);

    }


    public function updateStoreName(Request $request)
    {
        if (is_null($request->name) || empty($request->name)) {
            return redirect()->back()->with('error', 'Store Name is required.');
        }

        $newName = $request->name;
        $newSlug = Str::slug($newName);

        $existingStore = Store::whereRaw('LOWER(TRIM(name)) = ?', [trim(strtolower($request->name))])
            ->where("id", '!=', $request->store_id)
            ->first();

        if (!$existingStore) {
            // Now update the target store
            $store = Store::find($request->store_id);

            if ($store) {
                $store->name = $newName;
                $store->slug = $newSlug;

                // Handle subdomain update
                $parsedUrl = parse_url($store->url);
                $host = $parsedUrl['host'] ?? $store->url;
                $storeSubDomain = env("STORE_SUB_DOMAIN");
                if (Str::endsWith($host, "." . $storeSubDomain)) {
                    $newDomain = $this->newDomainSlug($newSlug);
                    $store->url = $newDomain;

                    $domain = Domain::where("name", $host)->where("status", "Active")->where("store_id", $store->id)->first();
                    if (isset($domain)) {
                        $domain->name = $newDomain;
                        $domain->save();
                    }
                }
                $store->save();

                $HeaderSetting = Headersetting::where("store_id", $store->id)->first();
                if (isset($HeaderSetting)) {
                    $HeaderSetting->website_name = $newName;
                    $HeaderSetting->save();
                }

                return redirect()->back()->with('success', 'Store Name Update Successfully.');
            }
        } else {
            return redirect()->back()->with('error', 'Store Name already exists.');
        }
    }


    public function newDomainSlug($baseSlug)
    {
        $subDomainBase = $baseSlug;
        $storeSubDomain = env("STORE_SUB_DOMAIN");
        $attempt = 1;

        do {
            $subDomainCandidate = $subDomainBase . '.' . $storeSubDomain;

            $domainExists = Domain::where('name', $subDomainCandidate)
                ->where('status', '!=', 'Rejected')
                ->exists();

            if ($domainExists) {
                $subDomainBase = $baseSlug . '-' . $attempt;
                $attempt++;
            }
        } while ($domainExists);

        return $subDomainCandidate;
    }


}
