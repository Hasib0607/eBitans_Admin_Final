<?php

namespace App\Http\Controllers;

use App\Http\Controllers\PaymentGateway\AcceptPlanController;
use App\Logic\Providers\cPanelApi;
use App\Models\Activity;
use App\Models\Addon;
use App\Models\AddonsApi;
use App\Models\AddonsExpired;
use App\Models\AddonsOrder;
use App\Models\AdminUserAnalytics;
use App\Models\BusinessCategory;
use App\Models\Category;
use App\Models\ClientActivitieComments;
use App\Models\Currency;
use App\Models\Customer;
use App\Models\DemoStoreData;
use App\Models\Designlist;
use App\Models\Digitalcontent;
use App\Models\Domain;
use App\Models\Iconpack;
use App\Models\Invoicepurchase;
use App\Models\Message;
use App\Models\Mobileapp;
use App\Models\Modulus;
use App\Models\ModulusPayment;
use App\Models\Notification;
use App\Models\Order;
use App\Models\Paymentgateway;
use App\Models\Plan;
use App\Models\Planorder;
use App\Models\Product;
use App\Models\QuickLogin;
use App\Models\Referral;
use App\Models\RegistrationFee;
use App\Models\Staff;
use App\Models\Store;
use App\Models\StorePurchaseHistory;
use App\Models\Superrole;
use App\Models\Supersetting;
use App\Models\Superstaff;
use App\Models\SuperstaffSalesCommission;
use App\Models\SuperstaffSalesCommissionBalance;
use App\Models\Template;
use App\Models\Temposition;
use App\Models\Themecustomize;
use App\Models\Tricket;
use App\Models\User;
use App\Models\Websitesetup;
use App\Models\WhatsAppMessage;
use App\Models\ZoneRecord;
use Carbon\Carbon;
use DB;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class StoreDemoDataController extends Controller
{
    /**
     * Show all categories list
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function storeCategoryList()
    {
        $categories = DemoStoreData::where("type", "category")->paginate(20);
        return view('superadmin.storeDemoData.category.index', ['categories' => $categories]);
    }

    /**
     * Show category add form
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function storeCategoryCreate()
    {
        $categories = BusinessCategory::with('subcategories')->whereNull('parent_id')->get();
        return view('superadmin.storeDemoData.category.create', ['categories' => $categories]);
    }

    /**
     * Store category details
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeCategorySave(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_name' => 'required',
            'category_image' => 'required',
            'category_id' => 'required',
        ], [
            'category_name.required' => 'Please enter category name.',
            'category_image.required' => 'Please enter category image.',
            'category_id.required' => 'Please enter selected category.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        } else {
            $demoStoreData = new DemoStoreData();

            $demoStoreData->category_name = $request->category_name;
            $demoStoreData->category_image = $request->category_image;

            if (isset($request->category_id) && !empty($request->category_id)) {
                $categoryIds = implode(',', $request->category_id);
                $demoStoreData->category_id = $categoryIds ?? NULL;
            }

            $demoStoreData->type = "category";
            $demoStoreData->save();

            Session::flash('message', 'Record Saved Successfully');
            return redirect()->route('superadmin.store.category.list');
        }

    }

    /**
     * Show category edit form
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function storeCategoryEdit($id)
    {
        if (!isset($id) || empty($id)) {
            Session::flash('message', 'Record ID missing');
            return redirect()->route('superadmin.store.category.list');
        }

        $demoStoreData = DemoStoreData::where("id", $id)->where("type", "category")->first();

        if (isset($demoStoreData)) {
            $categories = BusinessCategory::with('subcategories')->whereNull('parent_id')->get();
            return view('superadmin.storeDemoData.category.edit', [
                'data' => $demoStoreData,
                'categories' => $categories
            ]);
        } else {
            Session::flash('message', 'Record Does Not Exist');
            return redirect()->route('superadmin.store.category.list');
        }
    }

    /**
     * Update category
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeCategoryUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_name' => 'required',
            'category_id' => 'required',
        ], [
            'category_name.required' => 'Please enter category name.',
            'category_id.required' => 'Please enter selected category.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        } else {
            $demoStoreData = DemoStoreData::where("id", $request->id)->where("type", "category")->first();

            if (isset($demoStoreData)) {
                $demoStoreData->category_name = $request->category_name;
                $demoStoreData->category_image = $request->category_image;

                if (isset($request->category_id) && !empty($request->category_id)) {
                    $categoryIds = implode(',', $request->category_id);
                    $demoStoreData->category_id = $categoryIds ?? NULL;
                }

                $demoStoreData->update();

                Session::flash('message', 'Record Updated Successfully');
                return redirect()->route('superadmin.store.category.list');
            } else {
                Session::flash('message', 'Record Does Not Exist');
                return redirect()->route('superadmin.store.category.list');
            }
        }

    }

    /**
     * Delete category
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeCategoryDelete($id)
    {
        if (!isset($id) || empty($id)) {
            Session::flash('message', 'Record ID missing');
            return redirect()->route('superadmin.store.category.list');
        }

        $demoStoreData = DemoStoreData::where("id", $id)->where("type", "category")->first();
        if (isset($demoStoreData)) {
            $demoStoreData->delete();

            Session::flash('message', 'Record Deleted Successfully');
            return redirect()->route('superadmin.store.category.list');
        } else {
            Session::flash('message', 'Record Does Not Exist');
            return redirect()->route('superadmin.store.category.list');
        }

    }

    /**
     * Show all product list
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function storeProductList()
    {
        $products = DemoStoreData::where("type", "product")->paginate(20);
        return view('superadmin.storeDemoData.product.index', ['products' => $products]);
    }

    /**
     * Show product add form
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function storeProductCreate()
    {
        $categories = BusinessCategory::with('subcategories')->whereNull('parent_id')->get();
        return view('superadmin.storeDemoData.product.create', ['categories' => $categories]);
    }

    /**
     * Store product
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \WebPConvert\Convert\Exceptions\ConversionFailedException
     */
    public function storeProductSave(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_name' => 'required',
            'product_image' => 'required',
            'category_id' => 'required',
        ], [
            'product_name.required' => 'Please enter product name.',
            'product_image.required' => 'Please enter product image.',
            'category_id.required' => 'Please enter selected category.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        } else {
            $demoStoreData = new DemoStoreData();

            $demoStoreData->product_name = $request->product_name;

            if ($request->file('product_image')) {
                $image = $request->file('product_image');
                $imageUploadPath = 'assets/images/product/';
                $imageName = uploadFile($image, $imageUploadPath);
                $demoStoreData->product_image = $imageName;
            }

            if (isset($request->category_id) && !empty($request->category_id)) {
                $categoryIds = implode(',', $request->category_id);
                $demoStoreData->category_id = $categoryIds ?? NULL;
            }

            $demoStoreData->type = "product";
            $demoStoreData->save();

            Session::flash('message', 'Record Saved Successfully');
            return redirect()->route('superadmin.store.product.list');
        }

    }

    /**
     * Show product edit form
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function storeProductEdit($id)
    {
        if (!isset($id) || empty($id)) {
            Session::flash('message', 'Record ID missing');
            return redirect()->route('superadmin.store.product.list');
        }

        $demoStoreData = DemoStoreData::where("id", $id)->where("type", "product")->first();

        if (isset($demoStoreData)) {
            $categories = BusinessCategory::with('subcategories')->whereNull('parent_id')->get();
            return view('superadmin.storeDemoData.product.edit', [
                'data' => $demoStoreData,
                'categories' => $categories
            ]);
        } else {
            Session::flash('message', 'Record Does Not Exist');
            return redirect()->route('superadmin.store.product.list');
        }
    }

    /**
     * Update product
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \WebPConvert\Convert\Exceptions\ConversionFailedException
     */
    public function storeProductUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_name' => 'required',
            'category_id' => 'required',
        ], [
            'product_name.required' => 'Please enter product name.',
            'category_id.required' => 'Please enter selected category.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        } else {
            $demoStoreData = DemoStoreData::where("id", $request->id)->where("type", "product")->first();

            if (isset($demoStoreData)) {
                $demoStoreData->product_name = $request->product_name;

                if ($request->file('product_image')) {
                    $image = $request->file('product_image');
                    $imageUploadPath = 'assets/images/product/';
                    $imageName = updateFile($image, $imageUploadPath, $demoStoreData->product_image);
                    $demoStoreData->product_image = $imageName;
                }

                if (isset($request->category_id) && !empty($request->category_id)) {
                    $categoryIds = implode(',', $request->category_id);
                    $demoStoreData->category_id = $categoryIds ?? NULL;
                }

                $demoStoreData->update();

                Session::flash('message', 'Record Updated Successfully');
                return redirect()->route('superadmin.store.product.list');
            } else {
                Session::flash('message', 'Record Does Not Exist');
                return redirect()->route('superadmin.store.product.list');
            }
        }

    }

    /**
     * Delete product
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeProductDelete($id)
    {
        if (!isset($id) || empty($id)) {
            Session::flash('message', 'Record ID missing');
            return redirect()->route('superadmin.store.product.list');
        }

        $demoStoreData = DemoStoreData::where("id", $id)->where("type", "product")->first();
        if (isset($demoStoreData)) {
            $demoStoreData->delete();

            Session::flash('message', 'Record Deleted Successfully');
            return redirect()->route('superadmin.store.product.list');
        } else {
            Session::flash('message', 'Record Does Not Exist');
            return redirect()->route('superadmin.store.product.list');
        }

    }


    /**
     * Show all sliders list
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function storeSliderList()
    {
        $sliders = DemoStoreData::where("type", "slider")->paginate(20);
        return view('superadmin.storeDemoData.slider.index', ['sliders' => $sliders]);
    }

    /**
     * Show slider add form
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function storeSliderCreate()
    {
        $categories = BusinessCategory::with('subcategories')->whereNull('parent_id')->get();
        return view('superadmin.storeDemoData.slider.create', ['categories' => $categories]);
    }

    /**
     * Store slider
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \WebPConvert\Convert\Exceptions\ConversionFailedException
     */
    public function storeSliderSave(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'slider_image' => 'required',
            'category_id' => 'required',
        ], [
            'slider_image.required' => 'Please enter slider image.',
            'category_id.required' => 'Please enter selected category.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        } else {
            $demoStoreData = new DemoStoreData();

            if ($request->file('slider_image')) {
                $image = $request->file('slider_image');
                $imageUploadPath = 'assets/images/slider/';
                $imageName = uploadFile($image, $imageUploadPath);
                $demoStoreData->slider_image = $imageName;
            }

            if (isset($request->category_id) && !empty($request->category_id)) {
                $categoryIds = implode(',', $request->category_id);
                $demoStoreData->category_id = $categoryIds ?? NULL;
            }

            $demoStoreData->type = "slider";
            $demoStoreData->save();

            Session::flash('message', 'Record Saved Successfully');
            return redirect()->route('superadmin.store.slider.list');
        }

    }

    /**
     * Show slider edit form
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function storeSliderEdit($id)
    {
        if (!isset($id) || empty($id)) {
            Session::flash('message', 'Record ID missing');
            return redirect()->route('superadmin.store.slider.list');
        }

        $demoStoreData = DemoStoreData::where("id", $id)->where("type", "slider")->first();

        if (isset($demoStoreData)) {
            $categories = BusinessCategory::with('subcategories')->whereNull('parent_id')->get();
            return view('superadmin.storeDemoData.slider.edit', [
                'data' => $demoStoreData,
                'categories' => $categories
            ]);
        } else {
            Session::flash('message', 'Record Does Not Exist');
            return redirect()->route('superadmin.store.slider.list');
        }
    }

    /**
     * Update slider
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \WebPConvert\Convert\Exceptions\ConversionFailedException
     */
    public function storeSliderUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required',
        ], [
            'category_id.required' => 'Please enter selected category.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        } else {
            $demoStoreData = DemoStoreData::where("id", $request->id)->where("type", "slider")->first();

            if (isset($demoStoreData)) {
                if ($request->file('slider_image')) {
                    $image = $request->file('slider_image');
                    $imageUploadPath = 'assets/images/slider/';
                    $imageName = updateFile($image, $imageUploadPath, $demoStoreData->product_image);
                    $demoStoreData->slider_image = $imageName;
                }

                if (isset($request->category_id) && !empty($request->category_id)) {
                    $categoryIds = implode(',', $request->category_id);
                    $demoStoreData->category_id = $categoryIds ?? NULL;
                }

                $demoStoreData->update();

                Session::flash('message', 'Record Updated Successfully');
                return redirect()->route('superadmin.store.slider.list');
            } else {
                Session::flash('message', 'Record Does Not Exist');
                return redirect()->route('superadmin.store.slider.list');
            }
        }

    }

    /**
     * Delete slider
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeSliderDelete($id)
    {
        if (!isset($id) || empty($id)) {
            Session::flash('message', 'Record ID missing');
            return redirect()->route('superadmin.store.slider.list');
        }

        $demoStoreData = DemoStoreData::where("id", $id)->where("type", "slider")->first();
        if (isset($demoStoreData)) {
            $demoStoreData->delete();

            Session::flash('message', 'Record Deleted Successfully');
            return redirect()->route('superadmin.store.slider.list');
        } else {
            Session::flash('message', 'Record Does Not Exist');
            return redirect()->route('superadmin.store.slider.list');
        }

    }


    /**
     * Show all banners list
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function storeBannerList()
    {
        $banners = DemoStoreData::where("type", "banner")->paginate(20);
        return view('superadmin.storeDemoData.banner.index', ['banners' => $banners]);
    }

    /**
     * Show banner add form
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function storeBannerCreate()
    {
        $categories = BusinessCategory::with('subcategories')->whereNull('parent_id')->get();
        return view('superadmin.storeDemoData.banner.create', ['categories' => $categories]);
    }

    /**
     * Store banner
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \WebPConvert\Convert\Exceptions\ConversionFailedException
     */
    public function storeBannerSave(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'banner_image' => 'required',
            'category_id' => 'required',
        ], [
            'banner_image.required' => 'Please enter banner image.',
            'category_id.required' => 'Please enter selected category.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        } else {
            $demoStoreData = new DemoStoreData();

            if ($request->file('banner_image')) {
                $image = $request->file('banner_image');
                $imageUploadPath = 'assets/images/banner/';
                $imageName = uploadFile($image, $imageUploadPath);
                $demoStoreData->banner_image = $imageName;
            }

            if (isset($request->category_id) && !empty($request->category_id)) {
                $categoryIds = implode(',', $request->category_id);
                $demoStoreData->category_id = $categoryIds ?? NULL;
            }

            $demoStoreData->type = "banner";
            $demoStoreData->save();

            Session::flash('message', 'Record Saved Successfully');
            return redirect()->route('superadmin.store.banner.list');
        }

    }

    /**
     * Show banner edit form
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function storeBannerEdit($id)
    {
        if (!isset($id) || empty($id)) {
            Session::flash('message', 'Record ID missing');
            return redirect()->route('superadmin.store.banner.list');
        }

        $demoStoreData = DemoStoreData::where("id", $id)->where("type", "banner")->first();

        if (isset($demoStoreData)) {
            $categories = BusinessCategory::with('subcategories')->whereNull('parent_id')->get();
            return view('superadmin.storeDemoData.banner.edit', [
                'data' => $demoStoreData,
                'categories' => $categories
            ]);
        } else {
            Session::flash('message', 'Record Does Not Exist');
            return redirect()->route('superadmin.store.banner.list');
        }
    }

    /**
     * Update banner
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \WebPConvert\Convert\Exceptions\ConversionFailedException
     */
    public function storeBannerUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required',
        ], [
            'category_id.required' => 'Please enter selected category.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        } else {
            $demoStoreData = DemoStoreData::where("id", $request->id)->where("type", "banner")->first();

            if (isset($demoStoreData)) {
                if ($request->file('banner_image')) {
                    $image = $request->file('banner_image');
                    $imageUploadPath = 'assets/images/banner/';
                    $imageName = updateFile($image, $imageUploadPath, $demoStoreData->product_image);
                    $demoStoreData->banner_image = $imageName;
                }

                if (isset($request->category_id) && !empty($request->category_id)) {
                    $categoryIds = implode(',', $request->category_id);
                    $demoStoreData->category_id = $categoryIds ?? NULL;
                }

                $demoStoreData->update();

                Session::flash('message', 'Record Updated Successfully');
                return redirect()->route('superadmin.store.banner.list');
            } else {
                Session::flash('message', 'Record Does Not Exist');
                return redirect()->route('superadmin.store.banner.list');
            }
        }

    }

    /**
     * Delete banner
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeBannerDelete($id)
    {
        if (!isset($id) || empty($id)) {
            Session::flash('message', 'Record ID missing');
            return redirect()->route('superadmin.store.banner.list');
        }

        $demoStoreData = DemoStoreData::where("id", $id)->where("type", "banner")->first();
        if (isset($demoStoreData)) {
            $demoStoreData->delete();

            Session::flash('message', 'Record Deleted Successfully');
            return redirect()->route('superadmin.store.banner.list');
        } else {
            Session::flash('message', 'Record Does Not Exist');
            return redirect()->route('superadmin.store.banner.list');
        }

    }


    /**
     * Show all themes list
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function storeThemeList()
    {
        $themes = DemoStoreData::where("type", "theme")->paginate(20);
        return view('superadmin.storeDemoData.theme.index', ['themes' => $themes]);
    }

    /**
     * Show theme add form
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function storeThemeCreate()
    {
        $categories = BusinessCategory::with('subcategories')->whereNull('parent_id')->get();
        return view('superadmin.storeDemoData.theme.create', ['categories' => $categories]);
    }

    /**
     * Store theme
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \WebPConvert\Convert\Exceptions\ConversionFailedException
     */
    public function storeThemeSave(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'theme_value' => 'required',
            'category_id' => 'required',
        ], [
            'theme_value.required' => 'Please select theme.',
            'category_id.required' => 'Please enter selected category.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        } else {
            $demoStoreData = new DemoStoreData();

            $demoStoreData->theme_value = $request->theme_value;

            if (isset($request->category_id) && !empty($request->category_id)) {
                $categoryIds = implode(',', $request->category_id);
                $demoStoreData->category_id = $categoryIds ?? NULL;
            }

            $demoStoreData->type = "theme";
            $demoStoreData->save();

            Session::flash('message', 'Record Saved Successfully');
            return redirect()->route('superadmin.store.theme.list');
        }

    }

    /**
     * Show theme edit form
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function storeThemeEdit($id)
    {
        if (!isset($id) || empty($id)) {
            Session::flash('message', 'Record ID missing');
            return redirect()->route('superadmin.store.theme.list');
        }

        $demoStoreData = DemoStoreData::where("id", $id)->where("type", "theme")->first();

        if (isset($demoStoreData)) {
            $categories = BusinessCategory::with('subcategories')->whereNull('parent_id')->get();
            return view('superadmin.storeDemoData.theme.edit', [
                'data' => $demoStoreData,
                'categories' => $categories
            ]);
        } else {
            Session::flash('message', 'Record Does Not Exist');
            return redirect()->route('superadmin.store.theme.list');
        }
    }

    /**
     * Update theme
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \WebPConvert\Convert\Exceptions\ConversionFailedException
     */
    public function storeThemeUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'theme_value' => 'required',
            'category_id' => 'required',
        ], [
            'theme_value.required' => 'Please select theme.',
            'category_id.required' => 'Please enter selected category.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        } else {
            $demoStoreData = DemoStoreData::where("id", $request->id)->where("type", "theme")->first();

            if (isset($demoStoreData)) {
                $demoStoreData->theme_value = $request->theme_value;

                if (isset($request->category_id) && !empty($request->category_id)) {
                    $categoryIds = implode(',', $request->category_id);
                    $demoStoreData->category_id = $categoryIds ?? NULL;
                }

                $demoStoreData->update();

                Session::flash('message', 'Record Updated Successfully');
                return redirect()->route('superadmin.store.theme.list');
            } else {
                Session::flash('message', 'Record Does Not Exist');
                return redirect()->route('superadmin.store.theme.list');
            }
        }

    }

    /**
     * Delete theme
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeThemeDelete($id)
    {
        if (!isset($id) || empty($id)) {
            Session::flash('message', 'Record ID missing');
            return redirect()->route('superadmin.store.theme.list');
        }

        $demoStoreData = DemoStoreData::where("id", $id)->where("type", "theme")->first();
        if (isset($demoStoreData)) {
            $demoStoreData->delete();

            Session::flash('message', 'Record Deleted Successfully');
            return redirect()->route('superadmin.store.theme.list');
        } else {
            Session::flash('message', 'Record Does Not Exist');
            return redirect()->route('superadmin.store.theme.list');
        }

    }


    /**
     * Show all header list
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function storeHeaderList()
    {
        $headers = DemoStoreData::where("type", "header")->paginate(20);
        return view('superadmin.storeDemoData.header.index', ['headers' => $headers]);
    }

    /**
     * Show header add form
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function storeHeaderCreate()
    {
        $categories = BusinessCategory::with('subcategories')->whereNull('parent_id')->get();
        return view('superadmin.storeDemoData.header.create', ['categories' => $categories]);
    }

    /**
     * Store header
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \WebPConvert\Convert\Exceptions\ConversionFailedException
     */
    public function storeHeaderSave(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'header_color' => 'required',
            'category_id' => 'required',
        ], [
            'header_color.required' => 'Please enter header color.',
            'category_id.required' => 'Please enter selected category.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        } else {
            $demoStoreData = new DemoStoreData();

            $demoStoreData->header_color = $request->header_color;

            if (isset($request->category_id) && !empty($request->category_id)) {
                $categoryIds = implode(',', $request->category_id);
                $demoStoreData->category_id = $categoryIds ?? NULL;
            }

            $demoStoreData->type = "header";
            $demoStoreData->save();

            Session::flash('message', 'Record Saved Successfully');
            return redirect()->route('superadmin.store.header.list');
        }

    }

    /**
     * Show header edit form
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function storeHeaderEdit($id)
    {
        if (!isset($id) || empty($id)) {
            Session::flash('message', 'Record ID missing');
            return redirect()->route('superadmin.store.header.list');
        }

        $demoStoreData = DemoStoreData::where("id", $id)->where("type", "header")->first();

        if (isset($demoStoreData)) {
            $categories = BusinessCategory::with('subcategories')->whereNull('parent_id')->get();
            return view('superadmin.storeDemoData.header.edit', [
                'data' => $demoStoreData,
                'categories' => $categories
            ]);
        } else {
            Session::flash('message', 'Record Does Not Exist');
            return redirect()->route('superadmin.store.header.list');
        }
    }

    /**
     * Update header
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \WebPConvert\Convert\Exceptions\ConversionFailedException
     */
    public function storeHeaderUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'header_color' => 'required',
            'category_id' => 'required',
        ], [
            'header_color.required' => 'Please enter header color.',
            'category_id.required' => 'Please enter selected category.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        } else {
            $demoStoreData = DemoStoreData::where("id", $request->id)->where("type", "header")->first();

            if (isset($demoStoreData)) {
                $demoStoreData->header_color = $request->header_color;

                if (isset($request->category_id) && !empty($request->category_id)) {
                    $categoryIds = implode(',', $request->category_id);
                    $demoStoreData->category_id = $categoryIds ?? NULL;
                }

                $demoStoreData->update();

                Session::flash('message', 'Record Updated Successfully');
                return redirect()->route('superadmin.store.header.list');
            } else {
                Session::flash('message', 'Record Does Not Exist');
                return redirect()->route('superadmin.store.header.list');
            }
        }

    }

    /**
     * Delete header
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeHeaderDelete($id)
    {
        if (!isset($id) || empty($id)) {
            Session::flash('message', 'Record ID missing');
            return redirect()->route('superadmin.store.header.list');
        }

        $demoStoreData = DemoStoreData::where("id", $id)->where("type", "header")->first();
        if (isset($demoStoreData)) {
            $demoStoreData->delete();

            Session::flash('message', 'Record Deleted Successfully');
            return redirect()->route('superadmin.store.header.list');
        } else {
            Session::flash('message', 'Record Does Not Exist');
            return redirect()->route('superadmin.store.header.list');
        }

    }


}
