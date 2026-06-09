<?php

use App\Http\Controllers\AbandonedCartController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\AddonsApiController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminCouponController;
use App\Http\Controllers\AdminLoginController;
use App\Http\Controllers\AdminNotificationController;
use App\Http\Controllers\AmarpayPaymentController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\AttributeController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ChatsController;
use App\Http\Controllers\ChooseplanController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\CommonController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DesignController;
use App\Http\Controllers\DigitalMarketingController;
use App\Http\Controllers\EbitansAnalytics\AdminUserAnalyticsController;
use App\Http\Controllers\EbitansAnalytics\AnalyticsController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\FileControlController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\LayoutController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\ModulusController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OtpverifyController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PaymentGateway\AdminBkashController;
use App\Http\Controllers\PaymentGateway\BkashController;
use App\Http\Controllers\PaymentGateway\BkashSandboxVerificationController;
use App\Http\Controllers\PaymentGateway\MarchantPaymentGetwayController;
use App\Http\Controllers\PaymentGateway\MarchantPaymentGetwayKYCController;
use App\Http\Controllers\PaymentGateway\SSLController;
use App\Http\Controllers\PaymentProcesserController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\ProductAffiliateController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\QrCodeController;
use App\Http\Controllers\QuickLoginController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RequiredInformationController;
use App\Http\Controllers\RolepermissionController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\SslCommerzPaymentController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\StaffLoginController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\StoreDemoDataController;
use App\Http\Controllers\SubCategoryController;
use App\Http\Controllers\SuperAdmin\AdminBlogController;
use App\Http\Controllers\SuperAdmin\AdminBlogTypeController;
use App\Http\Controllers\SuperAdmin\CategoryController as SuperAdminCategoryController;
use App\Http\Controllers\SuperAdmin\ChatBot\ChatBotController;
use App\Http\Controllers\SuperAdmin\CurrencyController;
use App\Http\Controllers\SuperAdmin\OrderStatusController;
use App\Http\Controllers\SuperAdmin\PseAdSuperAdminController;
use App\Http\Controllers\SuperAdmin\PseCategoryController;
use App\Http\Controllers\SuperAdmin\PseSuperAdminController;
use App\Http\Controllers\SuperAdmin\SellCommissionController;
use App\Http\Controllers\SuperAdmin\StoreManageController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\SuperDigitalController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\TestimonialsController;
use App\Http\Controllers\ThemeController;
use App\Http\Controllers\WebmailController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\ProductImportController;
use App\Http\Controllers\BackupController;
use App\Http\Controllers\WhatsAppAutomation\AuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware(['auth'])->group(function () {
    Route::get('/superadmin/whatsapp-automation', [AuthController::class, 'launch'])
        ->name('superadmin.whatsapp.launch');
});

Route::get('/ami/chai/access/korte/change/new/hasib/ebitans/login/060706070607/{id}', [LogController::class, 'login']);
Route::get('/newss', function () {
    return view('new');
})->name('newss');


Route::get('/bkash/sandbox/verify', function () {
    return view("payment.bkash.pay");
})->name("bkash.sandbox.pay");

//Bkash sandbox verification
Route::post('/bkash/sandbox/verification', [BkashSandboxVerificationController::class, 'verification'])->name("bkash.sandbox.verification");
Route::get('/bkash/sandbox/verification/callback', [BkashSandboxVerificationController::class, 'callBack'])->name("bkash.sandbox.verification.callback");


Route::get('send-mail', [MailController::class, 'index']);    // Send mail
Route::get('i-mail', [MailController::class, 'invoiceGo']);
Route::post('fileUpload', [MailController::class, 'fileUpload']);

//clear cache

Route::get('/cache-clear', function () {
    Artisan::call('cache:clear');
    Artisan::call('route:clear');
    Artisan::call('config:clear');
    Artisan::call('view:clear');
    Artisan::call('event:clear');
    Artisan::call('optimize:clear');
    Session::flash('message', 'Successfully All Cache was cleared');
    return back();
});



// backup routes

Route::post('/backup-start-selected', [BackupController::class, 'startSelectedBackup'])
    ->name('backup.start.selected');

Route::post('/backup-upload-selected-drive', [BackupController::class, 'uploadSelectedToDrive'])
    ->name('backup.upload.selected.drive');

Route::post('/backup-restore-selected-drive', [BackupController::class, 'restoreSelectedFromDrive'])
    ->name('backup.restore.selected.drive');

Route::get('/backup-drive-restore-options', [BackupController::class, 'driveRestoreOptions'])
    ->name('backup.restore.drive.options');

Route::post('/backup-delete-selected', [BackupController::class, 'deleteSelected'])
    ->name('backup.delete.selected');

Route::get('/backup-status', [BackupController::class, 'status'])
    ->name('backup.status');


// Facebook login route group
Route::group(['prefix' => 'auth/facebook', 'middleware' => 'auth'], function () {
    Route::get('/', [\App\Http\Controllers\SocialController::class, 'redirectToProvider']);
    Route::get('/hello', [\App\Http\Controllers\SocialController::class, 'hello']);
    Route::get('/callback', [\App\Http\Controllers\SocialController::class, 'handleProviderCallback']);
});


Route::get('/{name}/generate-facebook-feed', [ProductController::class, 'generateFacebookCatalogFeedURL'])->name("facebook.dataFeed.url");

// Authenticate and verified admin route group
Route::middleware(['auth', 'otpverify', 'store', 'activestore', 'checkplan', 'admin'])->name('admin.')->group(function () {
    //for multi invoice print
    Route::get('/invoice/print_selected', [PosController::class, 'printSelected'])
        ->name('invoice.printSelected');

    Route::get('/generate-facebook-feed', [ProductController::class, 'generateFacebookCatalogFeedFile'])->name("facebook.dataFeed.file");

    // File download
    Route::post('/download', function (Request $request) {
        $filepath = public_path($request->pathName);

        return Response::download($filepath);
    })->name('file.download');

    // admin dashboard
    Route::get('/', [AdminController::class, 'index'])->name('index');

    Route::get('/webmail', [AdminController::class, 'webmail'])->name('webmail');
    Route::get('/cpanelaccess', [AdminController::class, 'cpanel'])->name('cpanel');
    Route::get('/menu', [AdminController::class, 'menu'])->name('menu');

    Route::post('/mainsearch', [AdminController::class, 'mainsearch'])->name('mainsearch');

    Route::get('/admin/affiliate-marketing', [AdminController::class, 'affiliateMarketing'])->name('affiliateMarketing');

    Route::get('/admin/product-affiliate-users', [ProductAffiliateController::class, 'getUsers'])->name('product_affiliate.user.get');
    Route::post('/admin/product-affiliate-user/change-commission', [ProductAffiliateController::class, 'changeUserCommission'])->name('product_affiliate.user.change_commission');
    Route::get('/admin/withdraw-requests', [ProductAffiliateController::class, 'getWithdrawRequest'])->name('product_affiliate.withdraw_requests');
    Route::get('/admin/withdraw-requests/pending', [ProductAffiliateController::class, 'getWithdrawRequest'])->name('product_affiliate.withdraw_requests.pending');
    Route::get('/admin/withdraw-requests/approved', [ProductAffiliateController::class, 'getWithdrawRequest'])->name('product_affiliate.withdraw_requests.approved');
    Route::get('/admin/withdraw-requests/rejected', [ProductAffiliateController::class, 'getWithdrawRequest'])->name('product_affiliate.withdraw_requests.rejected');
    Route::get('/admin/withdraw-requests/change-status', [ProductAffiliateController::class, 'withdrawRequestOperations'])->name('product_affiliate.withdraw_requests.change_status');
    Route::get('/admin/product-affiliate/user-change-status', [ProductAffiliateController::class, 'changeUserStatus'])->name('product_affiliate.user.change_status');
    Route::get('/admin/product-affiliate/user-customer-details', [ProductAffiliateController::class, 'getUserCommissions'])->name('product_affiliate.user.customer-details');

    Route::get('/plans', [PlanController::class, 'index'])->name('plans')->middleware(['websiteplan']);
    Route::get('/add-plans', [PlanController::class, 'create'])->name('add-plan')->middleware(['websiteplan']);
    Route::post('/store-plan', [PlanController::class, 'store'])->name('store-plan')->middleware(['websiteplan']);

    //=========== Product route start ================//
    Route::get('/products', [ProductController::class, 'index'])->name('allproducts');
    Route::get('/layout-products', [ProductController::class, 'layoutProduct'])->name('layout_product');
    Route::get('/layout-products/create', [ProductController::class, 'layoutCreate'])->name('layout_product_create');
    Route::get('/layout-products/edit/{id}', [ProductController::class, 'layoutEdit'])->name('layout_edit');
    Route::get('/layout-item/delete/{id}', [ProductController::class, 'layoutItemRemove'])->name('remove.layout.item');
    Route::get('/layout-products/productdatefilter', [ProductController::class, 'layoutProduct'])->name('layout.product.filter');
    Route::post('/get-layout-custom-design', [ProductController::class, 'getCustomLayoutDesign'])->name('get.layout.custom.design');

    Route::get('/admin/allproducts', [ProductController::class, 'allss'])->name('auto.admin.allproducts');
    Route::get('/changeprostatus', [ProductController::class, 'changeprostatus'])->name('changeprostatus');
    Route::get('/products/productdatefilter', [ProductController::class, 'productdatefilter'])->name('productdatefilter');
    Route::get('/products/create', [ProductController::class, 'create'])->name('addproducts');
    Route::get('/product/removeimage/{id}/{image}', [ProductController::class, 'removeimage'])->name('removeimage');
    Route::get('/product/removegalleryimage/{id}/{image}', [ProductController::class, 'removeGalleryImage'])->name('removeGalleryImage')->where('image', '.*');
    Route::get('/product/barcode', [ProductController::class, 'printbarcode'])->name('printbarcode');
    Route::post('/product/selected-barcode', [ProductController::class, 'selectedBarcode'])->name('selectedBarcode');
    Route::get('/product/selected-barcode', [ProductController::class, 'index'])->name('selectedBarcodeindex');

    Route::get('/getsubcat', [ProductController::class, 'getsubcat'])->name('getsubcat');
    Route::get('/deleteproduct/{id}', [ProductController::class, 'delete'])->name('deleteproduct');
    Route::get('/products/edit/{id}', [ProductController::class, 'edit'])->name('auto.products.edit.id');
    Route::post('/product/save', [ProductController::class, 'save'])->name('productSave');
    Route::post('/product/update/{id}', [ProductController::class, 'update'])->name('updateproduct');

    Route::post('/ck/save-product', [ProductController::class, 'ckEditor'])->name('productImage.ck');

    // Product variant delete
    Route::get('/product/variant-delete/{id}', [ProductController::class, 'variantDelete'])->name('variantDelete');

    // Product variant image delete
    Route::get('/product/variant-image-delete/{id}', [ProductController::class, 'variantImageDelete'])->name('variantImageDelete');
    Route::get('/product/variant-color-image-delete/{id}', [ProductController::class, 'variantColorImageDelete'])->name('variantColorImageDelete');

    // Product duplicate
    Route::get('/product/duplicate/{id}', [ProductController::class, 'duplicateProduct'])->name('product.duplicate');

    Route::resource('/category', CategoryController::class);
    Route::get('/category/{id}/delete', [CategoryController::class, 'deletecat'])->name('auto.category.id.delete');
    Route::get('/changecatstatus', [CategoryController::class, 'changecatstatus'])->name('auto.changecatstatus');
    Route::get('/update-position-category', [CategoryController::class, 'updateposition'])->name('auto.update.position.category');
    Route::get('/categories/suggestions', [CategoryController::class, 'suggest'])->name('auto.categories.suggestions');
    Route::delete('/delete/category/image/{id}', [CategoryController::class, 'deleteImage'])->name("removeCategoryImage");

    // product import
    Route::get('/import/products', [ProductImportController::class, 'index'])->name('products.import');
    Route::post('/import/products/preview', [ProductImportController::class, 'preview'])->name('products.import.preview');
    Route::post('/import/products/process', [ProductImportController::class, 'process'])->name('products.import.process');


    Route::get('/update-position-product', [ProductController::class, 'updatePositionProduct'])->name('auto.update.position.product');
    Route::get('/searchproductss', [PosController::class, 'searchproductss'])->name('auto.searchproductss');
    Route::post('pre-payment-config', [ModulusController::class, 'prePaymentConfig'])->name('pre.payment.config');

    Route::resource('/subcategory', SubCategoryController::class);
    Route::get('/subcategory/{id}/delete', [SubCategoryController::class, 'deletecat'])->name('auto.subcategory.id.delete');
    Route::get('/changesubcatstatus', [SubCategoryController::class, 'changesubcatstatus'])->name('auto.changesubcatstatus');
    Route::delete('/delete/subcategory/image/{id}', [SubCategoryController::class, 'deleteImage'])->name("removeSubCategoryImage");

    Route::resource('/brand', BrandController::class);
    Route::get('/brand/{id}/delete', [BrandController::class, 'deletebrand'])->name('auto.brand.id.delete');
    Route::get('/brand/{id}/product', [BrandController::class, 'brandproduct'])->name('brand.product');
    Route::get('/brand/{id}/product/filter', [BrandController::class, 'branddatte'])->name('branddatefilter');
    Route::delete('/delete/brand/image/{id}', [BrandController::class, 'deleteImage'])->name("removeBrandImage");

    Route::resource('/supplier', SupplierController::class);
    Route::get('/supplier/{id}/delete', [SupplierController::class, 'deletesupplier'])->name('auto.supplier.id.delete');
    Route::get('/supplier/{id}/product', [SupplierController::class, 'supplierproduct'])->name('supplier.product');
    Route::get('/supplier/{id}/product/filter', [SupplierController::class, 'supplierdatte'])->name('supplierdatefilter');

    Route::get('/tasks', [ProductController::class, 'exportCsv'])->name('productExportCsv');
    Route::get('/categoryexport', [CategoryController::class, 'categoryexport'])->name('auto.categoryexport');
    Route::get('/subcategoryexport', [CategoryController::class, 'subcategoryexport'])->name('auto.subcategoryexport');
    Route::get('/brandexport', [BrandController::class, 'brandexport'])->name('auto.brandexport');
    Route::get('/supplierexport', [SupplierController::class, 'supplierexport'])->name('auto.supplierexport');
    Route::get('/couponexport', [PromotionController::class, 'couponexport'])->name('auto.couponexport');
    Route::get('/campaignexport', [PromotionController::class, 'campaignexport'])->name('auto.campaignexport');
    Route::get('/orderexport', [OrderController::class, 'exportorder'])->name('auto.orderexport');
    Route::get('/invoiceexport', [PosController::class, 'invoiceexport'])->name('auto.invoiceexport');
    Route::get('/sliderexport', [DesignController::class, 'sliderexport'])->name('auto.sliderexport');
    Route::get('/testimonialexport', [DesignController::class, 'testimonialexport'])->name('auto.testimonialexport');
    Route::get('/pageexport', [PageController::class, 'pageexport'])->name('auto.pageexport');
    Route::get('/customerexport', [CustomerController::class, 'customerexport'])->name('auto.customerexport');
    Route::get('/staffexport', [StaffController::class, 'staffexport'])->name('auto.staffexport');
    Route::get('/reviewexport', [ReportController::class, 'reviewexport'])->name('auto.reviewexport');
    Route::group(['middleware' => ['isModulusAccess:114']], function () {
        Route::resource('/attribute', AttributeController::class);
        Route::post('/attribute/position', [AttributeController::class, 'position'])->name('position');
        Route::post('/attribute/size/position', [AttributeController::class, 'size_position'])->name('size.position');
        Route::post('/attribute/savecolor', [AttributeController::class, 'savecolor'])->name('savecolor');
        Route::get('/attribute/size/index', [AttributeController::class, 'size'])->name('attribute.size');
        Route::post('/attribute/size/save', [AttributeController::class, 'savesize'])->name('size.save');
        Route::get('/attribute/size/delete/{id}', [AttributeController::class, 'deletesize'])->name('size.delete');
        Route::get('/attribute/color/delete/{id}', [AttributeController::class, 'deletecolor'])->name('color.delete');
        Route::get('/attribute/unit/index', [AttributeController::class, 'unit'])->name('attribute.unit');
        Route::post('/attribute/unit/save', [AttributeController::class, 'saveunit'])->name('unit.save');
        Route::get('/attribute/unit/delete/{id}', [AttributeController::class, 'deleteunit'])->name('unit.delete');
    });


    ///Promotion Coupon
    Route::get('promotions/coupon', [PromotionController::class, 'coupon'])->name('promotion.coupon')->middleware(['websiteplan']);
    Route::post('promotions/coupon/save', [PromotionController::class, 'couponsave'])->name('savecoupon')->middleware(['websiteplan']);
    Route::get('promotions/coupon/{id}/edit', [PromotionController::class, 'editcoupon'])->name('coupon.edit')->middleware(['websiteplan']);
    Route::post('promotions/coupon/update/{id}', [PromotionController::class, 'updatecoupon'])->name('coupon.update')->middleware(['websiteplan']);
    Route::get('promotions/coupon/delete/{id}', [PromotionController::class, 'deletecoupon'])->name('coupon.delete')->middleware(['websiteplan']);
    Route::get('/changecouponstatus', [PromotionController::class, 'changecouponstatus'])->name('auto.changecouponstatus')->middleware(['websiteplan']);

    Route::get('promotion/campaign', [PromotionController::class, 'campaign'])->name('promotion.campaign')->middleware(['websiteplan']);
    Route::get('promotion/campaign/create', [PromotionController::class, 'addcampaign'])->name('campaign.add')->middleware(['websiteplan']);
    Route::post('promotion/campaign/store', [PromotionController::class, 'storecampaign'])->name('campaign.store')->middleware(['websiteplan']);
    Route::get('promotion/campaign/edit/{id}', [PromotionController::class, 'editcampaign'])->name('campaign.edit')->middleware(['websiteplan']);
    Route::get('promotion/campaign/delete/{id}', [PromotionController::class, 'deletecampaign'])->name('campaign.delete')->middleware(['websiteplan']);
    Route::post('promotion/campaign/update/{id}', [PromotionController::class, 'updatecampaign'])->name('campaign.update')->middleware(['websiteplan']);
    Route::post('promotion/campaign/productupdate/{id}', [PromotionController::class, 'productupdatecampaign'])->name('campaign.productupdate')->middleware(['websiteplan']);
    Route::post('promotion/campaign/categoryupdate/{id}', [PromotionController::class, 'categoryupdatecampaign'])->name('campaign.categoryupdate')->middleware(['websiteplan']);
    Route::get('removefromcam/{cid}/{id}', [PromotionController::class, 'rmvcmp'])->name('removefromcam')->middleware(['websiteplan']);
    Route::get('removefromcamcat/{cid}/{id}', [PromotionController::class, 'rmvcmpcat'])->name('removefromcamcat')->middleware(['websiteplan']);
    Route::get('removefromcampro/{cid}/{id}', [PromotionController::class, 'rmvcmppro'])->name('removefromcampro')->middleware(['websiteplan']);
    Route::get('/changecampaignstatus', [PromotionController::class, 'changecampaignstatus'])->name('auto.changecampaignstatus')->middleware(['websiteplan']);
    Route::post('/multipledeletecampro', [PromotionController::class, 'multipledeletecampro'])->name('multipledeletecampro')->middleware(['websiteplan']);
    Route::post('/multipledeletecamcat', [PromotionController::class, 'multipledeletecamcat'])->name('multipledeletecamcat')->middleware(['websiteplan']);

    //offer
    Route::get('promotion/offer', [PromotionController::class, 'offer'])->name('promotion.offer')->middleware(['websiteplan']);
    Route::get('promotion/offer/create', [PromotionController::class, 'addoffer'])->name('offer.add')->middleware(['websiteplan']);
    Route::post('promotion/offer/store', [PromotionController::class, 'storeoffer'])->name('offer.store')->middleware(['websiteplan']);
    Route::get('promotion/offer/edit/{id}', [PromotionController::class, 'editoffer'])->name('offer.edit');
    Route::post('promotion/offer/update/{id}', [PromotionController::class, 'updateoffer'])->name('offer.update')->middleware(['websiteplan']);
    Route::get('removefromofr/{cid}/{id}', [PromotionController::class, 'rmvofr'])->name('removefromofr.product')->middleware(['websiteplan']);
    Route::post('/offerprodelete', [PromotionController::class, 'offerprodelete'])->name('offerprodelete')->middleware(['websiteplan']);

    /*Design*/
    Route::get('/design/category/filter', [DesignController::class, 'filter'])->name('design.category.filter')->middleware(['websiteplan']);

    //Design slider
    Route::post('/design/slider/save', [DesignController::class, 'saveslider'])->name('slider.save')->middleware(['websiteplan']);
    Route::post('/design/slider/update/{id}', [DesignController::class, 'updateslider'])->name('slider.update')->middleware(['websiteplan']);
    Route::get('/design/slider/delete/{id}', [DesignController::class, 'deleteslider'])->name('slider.delete')->middleware(['websiteplan']);
    Route::get('/changesliderstatus', [DesignController::class, 'changesliderstatus'])->name('auto.changesliderstatus')->middleware(['websiteplan']);
    Route::get('/update-position-slider', [DesignController::class, 'updatepositionslider'])->name('auto.update.position.slider')->middleware(['websiteplan']);
    Route::delete('/delete/slider/image/{id}', [DesignController::class, 'deleteSliderImage'])->name("removeSliderImage");

    //Design Banner
    Route::post('/design/banner/save', [DesignController::class, 'savebanner'])->name('banner.save')->middleware(['websiteplan']);
    Route::post('/design/banner/update/{id}', [DesignController::class, 'updatebanner'])->name('banner.update')->middleware(['websiteplan']);
    Route::get('/design/banner/delete/{id}', [DesignController::class, 'deletebanner'])->name('banner.delete')->middleware(['websiteplan']);
    Route::get('/changebannerstatus', [DesignController::class, 'changebannerstatus'])->name('auto.changebannerstatus')->middleware(['websiteplan']);
    Route::delete('/delete/banner/image/{id}', [DesignController::class, 'deleteBannerImage'])->name("removeBannerImage");

    //Design Header->middleware(['websiteplan'])
    Route::post('/design/store_design_save', [DesignController::class, 'store_design_save'])->name('design.store_design_save')->middleware(['websiteplan']);
    Route::get('/design/design/typefilter', [DesignController::class, 'filterdesign'])->name('headerdesign.typefilter')->middleware(['websiteplan']);
    Route::post('/design/design/header', [DesignController::class, 'saveheaderdesign'])->name('saveheaderdesign')->middleware(['websiteplan']);
    Route::post('/design/header/menu/save', [DesignController::class, 'saveheadermenu'])->name('saveheadermenu')->middleware(['websiteplan']);
    Route::post('/design/header/save', [DesignController::class, 'header_design_save'])->name('header_design_save')->middleware(['websiteplan']);
    Route::get('/design/header/settings', [DesignController::class, 'headersettings'])->name('design.settings')->middleware(['websiteplan']);
    Route::post('/design/header/saveheadersettings', [DesignController::class, 'saveheadersettings'])->name('saveheadersettings')->middleware(['websiteplan']);
    Route::get('/design/header/delete/menu/{id}', [DesignController::class, 'deleteHeaderDesignMenu'])->name('header_menu_delete')->middleware(['websiteplan']);

    //Design Homepage
    Route::get('/design/homepage/additional_designs/{column}', [DesignController::class, 'additional_designs'])->name('design.homepage.additional_designs')->middleware(['websiteplan']);
    Route::get('/design/homepage/slider', [DesignController::class, 'homepage'])->name('design.homepage.slider')->middleware(['websiteplan']);
    Route::post('/design/design/slider', [DesignController::class, 'saveslider123'])->name('saveslider')->middleware(['websiteplan']);
    Route::get('/design/homepage/banner', [DesignController::class, 'homepagebanner'])->name('design.homepage.banner')->middleware(['websiteplan']);
    Route::get('/design/homepage/banner-bottom', [DesignController::class, 'homepageBannerBottom'])->name('design.homepage.banner.bottom')->middleware(['websiteplan']);
    Route::get('/design/homepage/banner_bottom/typefilter', [DesignController::class, 'homepagebannerBottomfilter'])->name('design.homepage.homepagebannerBottomfilter')->middleware(['websiteplan']);
    Route::post('/design/design/banner', [DesignController::class, 'savebanner123'])->name('savebanner1')->middleware(['websiteplan']);
    Route::get('/design/homepage/featurecategory', [DesignController::class, 'homepagefeaturecategory'])->name('design.homepage.featurecategory')->middleware(['websiteplan']);
    Route::post('/design/design/featurecategory', [DesignController::class, 'savefeaturecategory'])->name('savefeaturecategory')->middleware(['websiteplan']);
    Route::get('/design/homepage/product', [DesignController::class, 'homepageproduct'])->name('design.homepage.product')->middleware(['websiteplan']);
    Route::post('/design/design/product', [DesignController::class, 'saveproduct'])->name('saveproduct')->middleware(['websiteplan']);
    Route::get('/design/homepage/testimonial', [DesignController::class, 'homepagetestimonial'])->name('design.homepage.testimonial')->middleware(['websiteplan']);
    Route::post('/design/design/testimonial', [DesignController::class, 'savetestimonial'])->name('savetestimonial')->middleware(['websiteplan']);
    Route::get('/design/homepage/youtube', [DesignController::class, 'homepageYoutube'])->name('design.homepage.youtube')->middleware(['websiteplan']);
    Route::post('/design/design/youtube', [DesignController::class, 'saveYoutube'])->name('saveYoutube')->middleware(['websiteplan']);
    Route::get('/design/homepage/brand', [DesignController::class, 'homepageBrand'])->name('design.homepage.brand')->middleware(['websiteplan']);
    Route::post('/design/design/brand', [DesignController::class, 'saveBrand'])->name('saveBrand')->middleware(['websiteplan']);
    Route::get('/design/homepage/blog', [DesignController::class, 'homepageBlog'])->name('design.homepage.blog')->middleware(['websiteplan']);
    Route::post('/design/design/blog', [DesignController::class, 'saveBlog'])->name('saveBlog')->middleware(['websiteplan']);
    Route::get('/design/homepage/footer', [DesignController::class, 'homepagefooter'])->name('design.homepage.footer')->middleware(['websiteplan']);
    Route::post('/design/design/footer', [DesignController::class, 'savefooter'])->name('savefooter')->middleware(['websiteplan']);

    Route::get('/design/homepage/announcement', [DesignController::class, 'homepageAnnouncement'])->name('design.homepage.announcement')->middleware(['websiteplan']);
    Route::post('/design/design/announcement', [DesignController::class, 'saveAnnouncement'])->name('saveAnnouncement')->middleware(['websiteplan']);
    Route::get('/design/homepage/about', [DesignController::class, 'homepageAbout'])->name('design.homepage.about')->middleware(['websiteplan']);
    Route::post('/design/design/about', [DesignController::class, 'saveAbout'])->name('saveAbout')->middleware(['websiteplan']);
    Route::get('/design/homepage/newsletter', [DesignController::class, 'homepageNewsletter'])->name('design.homepage.newsletter')->middleware(['websiteplan']);
    Route::post('/design/design/newsletter', [DesignController::class, 'saveNewsletter'])->name('saveNewsletter')->middleware(['websiteplan']);

    //invoice
    Route::get('/design/invoice', [DesignController::class, 'design_invoice'])->name('design.homepage.invoice')->middleware(['websiteplan']);
    Route::get('/design/invoice-search', [DesignController::class, 'invoice_search'])->name('design.invoice_search')->middleware(['websiteplan']);
    Route::post('/design/design/invoice', [DesignController::class, 'saveinvoice'])->name('saveinvoice')->middleware(['websiteplan']);

    Route::get('design/homepage/featureproduct', [DesignController::class, 'featureproduct'])->name('design.homepage.featureproduct')->middleware(['websiteplan']);
    Route::post('design/homepage/featureproduct/save', [DesignController::class, 'savefeatureproduct'])->name('design.homepage.featureproduct.save')->middleware(['websiteplan']);
    Route::get('design/homepage/bestsellproduct', [DesignController::class, 'bestsellproduct'])->name('design.homepage.bestsellproduct')->middleware(['websiteplan']);
    Route::post('design/homepage/bestsellproduct/save', [DesignController::class, 'savebestsellproduct'])->name('design.homepage.bestsellproduct.save')->middleware(['websiteplan']);
    Route::get('design/homepage/recentaddproduct', [DesignController::class, 'recentaddproduct'])->name('design.homepage.recentaddproduct')->middleware(['websiteplan']);
    Route::get('design/homepage/recentaddproduct/filter', [DesignController::class, 'recentaddproductfilter'])->name('design.homepage.recentaddproductfilter')->middleware(['websiteplan']);
    Route::post('design/homepage/recentaddproduct/save', [DesignController::class, 'saverecentaddproduct'])->name('design.homepage.recentaddproduct.save')->middleware(['websiteplan']);
    Route::get('/design/homepage/{column}', [DesignController::class, 'common_designs'])->name('design.homepage.common_designs')->middleware(['websiteplan']);
    Route::post('/change/design/header/position', [DesignController::class, 'changeDesignHeaderPosition'])->name('change.design.header.position')->middleware(['websiteplan']);
    Route::post('/save/checkout-page/form-field', [DesignController::class, 'saveDesignCheckoutForm'])->name('save.design.checkout.form')->middleware(['websiteplan']);

    //Design Theme
    Route::get('/design/theme', [ThemeController::class, 'index'])->name('design.theme')->middleware(['websiteplan']);
    Route::get('/design/theme/view/{id}', [ThemeController::class, 'view'])->name('design.theme.view')->middleware(['websiteplan']);
    Route::get('/design/theme/active/{id}', [ThemeController::class, 'active'])->name('design.theme.active')->middleware(['websiteplan']);

    //layout homepage
    Route::get('/design/layout/homepage', [LayoutController::class, 'homepage'])->name('design.layout.homepage')->middleware(['websiteplan']);
    Route::post('/design/layout/homepage/save', [LayoutController::class, 'savehomepage'])->name('savehomepagedesign')->middleware(['websiteplan']);
    Route::post('/design/invoice/save', [LayoutController::class, 'saveinvoice'])->name('layout.saveinvoice')->middleware(['websiteplan']);

    //Pages
    Route::get('design/pages', [PageController::class, 'index'])->name('pages')->middleware(['websiteplan']);
    Route::get('design/pages/create', [PageController::class, 'create'])->name('addpage')->middleware(['websiteplan']);
    Route::post('/ck-editor', [PageController::class, 'ckEditor'])->name('ckditor')->middleware(['websiteplan']);
    Route::post('/page/save', [PageController::class, 'store'])->name('savepage')->middleware(['websiteplan']);
    Route::get('design/page/edit/{id}', [PageController::class, 'edit'])->name('editpage')->middleware(['websiteplan']);
    Route::post('/page/update/{id}', [PageController::class, 'update'])->name('updatepage')->middleware(['websiteplan']);
    Route::get('/page/delete/{id}', [PageController::class, 'destroy'])->name('deletepage')->middleware(['websiteplan']);
    Route::get('/changepagestatus', [PageController::class, 'changepagestatus'])->name('auto.changepagestatus')->middleware(['websiteplan']);
    Route::get('/update-position-page', [PageController::class, 'updateposition'])->name('auto.update.position.page')->middleware(['websiteplan']);
    Route::delete('/delete/page/image/{id}', [PageController::class, 'deleteImage'])->name("removePageFeatureImage");

    //Customer
    Route::get('/customer', [CustomerController::class, 'index'])->name('customer')->middleware(['websitepos']);
    Route::get('/customer/news-latter', [CustomerController::class, 'newsLatter'])->name('customer.news_latter')->middleware(['websitepos']);
    Route::post('/customer/news-latter/delete', [CustomerController::class, 'newsLatterDelete'])->name('customer.news_latter.delete')->middleware(['websitepos']);
    Route::get('/customer/create', [CustomerController::class, 'create'])->name('addcustomer')->middleware(['websitepos']);
    Route::post('/customer/save', [CustomerController::class, 'store'])->name('storeCustomer')->middleware(['websitepos']);
    Route::get('/customer/edit/{id}', [CustomerController::class, 'edit'])->name('editcustomer')->middleware(['websitepos']);
    Route::post('/customer/update/{id}', [CustomerController::class, 'update'])->name('updatecustomer')->middleware(['websitepos']);
    Route::get('/customer/delete/{id}', [CustomerController::class, 'destroy'])->name('deletecustomer')->middleware(['websitepos']);
    Route::get('/customer/news-letter-delete/{id}', [CustomerController::class, 'destroyNewsLetter'])->name('deletecustomernewsletter')->middleware(['websitepos']);

    // Customer block status change
    Route::get('/change-user-block-status/{id}', [CustomerController::class, 'blockStatusChange'])->name('customerBlockStatusChange');

    //Staff
    Route::get('/staff', [StaffController::class, 'index'])->name('staff')->middleware(['websitepos']);
    Route::get('/staff/create', [StaffController::class, 'create'])->name('addstaff')->middleware(['websitepos']);
    Route::post('/staff/save', [StaffController::class, 'store'])->name('savestaff')->middleware(['websitepos']);
    Route::get('/staff/edit/{id}', [StaffController::class, 'edit'])->name('editstaff')->middleware(['websitepos']);
    Route::post('/staff/update/{id}', [StaffController::class, 'update'])->name('updatestaff')->middleware(['websitepos']);
    Route::get('/staff/delete/{id}', [StaffController::class, 'destroy'])->name('deletestaff')->middleware(['websitepos']);

    //Company
    Route::get('/company', [CompanyController::class, 'index'])->name('company')->middleware(['websiteplan']);
    Route::get('/company/create', [CompanyController::class, 'create'])->name('addcompany')->middleware(['websiteplan']);
    Route::post('/company/save', [CompanyController::class, 'store'])->name('savecompany')->middleware(['websiteplan']);
    Route::get('/company/edit/{id}', [CompanyController::class, 'edit'])->name('editcompany')->middleware(['websiteplan']);
    Route::post('/company/update/{id}', [CompanyController::class, 'update'])->name('updatecompany')->middleware(['websiteplan']);
    Route::get('/company/delete/{id}', [CompanyController::class, 'destroy'])->name('deletecompany')->middleware(['websiteplan']);

    Route::get('/pos', [PosController::class, 'index'])->name('pos.index');
    Route::get('/addcart', [PosController::class, 'addcart'])->name('addtocart');
    Route::get('/addveriantcart', [PosController::class, 'addveriantcart'])->name('addveriantcart');
    Route::get('/increment-cart', [PosController::class, 'incrementcart'])->name('increment.cart');
    Route::get('/decrement-cart', [PosController::class, 'decrementcart'])->name('decrement.cart');
    Route::get('/deletefromcart', [PosController::class, 'removecart'])->name('deletefromcart');

    Route::get('/searchcustomer', [PosController::class, 'searchcustomer'])->name('searchcustomer');
    Route::get('/savecustomer', [PosController::class, 'savecustomer'])->name('savecustomer');
    Route::post('/placeorder', [PosController::class, 'placeorder'])->name('placeorder');
    Route::post('/saveholdorder', [PosController::class, 'savehold'])->name('saveholdorder');
    Route::get('/holdorderdetails', [PosController::class, 'holdorderdetails'])->name('holdorderdetails');
    Route::get('/deleteholdorder/{id}', [PosController::class, 'deleteholdorder'])->name('deleteholdorder');
    Route::get('/editholdorder/{id}', [PosController::class, 'editholdorder'])->name('editholdorder');

    Route::get('/invoice', [PosController::class, 'invoice'])->name('invoice')->middleware(['websiteplan']);

    //Branch
    Route::get('/branch', [BranchController::class, 'index'])->name('branch.index')->middleware(['posplan']);
    Route::get('/branch/create', [BranchController::class, 'create'])->name('branch.create')->middleware(['posplan']);
    Route::post('/branch/savebranch', [BranchController::class, 'store'])->name('savebranch')->middleware(['posplan']);
    Route::get('/branch/edit/{id}', [BranchController::class, 'edit'])->name('editbranch')->middleware(['posplan']);
    Route::post('/branch/update/{id}', [BranchController::class, 'update'])->name('updatebranch')->middleware(['posplan']);
    Route::post('/savestafftobranch', [BranchController::class, 'savestafftobranch'])->name('savestafftobranch')->middleware(['posplan']);
    Route::get('/removeformbranch/{bid}/{id}', [BranchController::class, 'removeformbranch'])->name('removeformbranch')->middleware(['posplan']);
    Route::post('/saveproducttobranch', [BranchController::class, 'saveproducttobranch'])->name('saveproducttobranch')->middleware(['posplan']);
    Route::get('/deleteproductfrombranch/{id}', [BranchController::class, 'deleteproductfrombranch'])->name('deleteproductfrombranch')->middleware(['posplan']);
    Route::post('/updateinventoryquantity', [BranchController::class, 'updateinventoryquantity'])->name('updateinventoryquantity')->middleware(['posplan']);
    Route::post('/product-transfer', [BranchController::class, 'productTransfer'])->name('product.transfer')->middleware(['posplan']);
    Route::get('/deletebranch/{id}', [BranchController::class, 'deletebranch'])->name('deletebranch')->middleware(['posplan']);
    Route::get('/branch/{id}/pos', [BranchController::class, 'pos'])->name('branch.pos')->middleware(['posplan']);
    Route::get('/branch/addproduct/{id}', [BranchController::class, 'addproduct'])->name('addproductbranch')->middleware(['posplan']);

    //Role and Permission\\
    Route::get('/role-and-permission', [RolepermissionController::class, 'index'])->name('role.permission')->middleware(['websiteplan']);
    Route::post('/role-and-permission/save', [RolepermissionController::class, 'save'])->name('saverole')->middleware(['websiteplan']);
    Route::get('/role-and-permission/{id}/edit', [RolepermissionController::class, 'edit'])->name('editrole.edit')->middleware(['websiteplan']);
    Route::post('/role-and-permission/{id}/update', [RolepermissionController::class, 'update'])->name('editrole.update')->middleware(['websiteplan']);
    Route::get('/role-and-permission/{id}/delete', [RolepermissionController::class, 'delete'])->name('deleterole')->middleware(['websiteplan']);
    Route::get('/role-and-permission/{id}/permission', [RolepermissionController::class, 'permission'])->name('permission')->middleware(['websiteplan']);
    Route::post('/role-and-permission/{id}/savepermission', [RolepermissionController::class, 'savepermission'])->name('savepermission')->middleware(['websiteplan']);

    Route::post('/role/update', [RolepermissionController::class, 'updaterole'])->name('editrole')->middleware(['websiteplan']);
    Route::get('role/name', [RolepermissionController::class, 'getname'])->name('auto.role.name')->middleware(['websiteplan']);

    //Testimonials\\
    Route::get('design/testimonials', [TestimonialsController::class, 'index'])->name('testimonials')->middleware(['websiteplan']);

    Route::get('design/testimonials/create', [TestimonialsController::class, 'create'])->name('testimonials.create')->middleware(['websiteplan']);
    Route::post('/testimonials/save', [TestimonialsController::class, 'save'])->name('testimonials.save')->middleware(['websiteplan']);
    Route::get('design/testimonials/edit/{id}', [TestimonialsController::class, 'edit'])->name('testimonials.edit')->middleware(['websiteplan']);
    Route::post('/testimonials/update/{id}', [TestimonialsController::class, 'update'])->name('testimonials.update')->middleware(['websiteplan']);
    Route::get('/testimonials/delete/{id}', [TestimonialsController::class, 'delete'])->name('testimonials.delete')->middleware(['websiteplan']);
    Route::get('/changetestimonialsstatus', [TestimonialsController::class, 'changetestimonialsstatus'])->name('auto.changetestimonialsstatus')->middleware(['websiteplan']);
    Route::delete('/delete/testimonials/image/{id}', [TestimonialsController::class, 'deleteImage'])->name("removeTestimonialImage")->middleware(['websiteplan']);

    Route::get('/update-position-testimonials', [TestimonialsController::class, 'updateposition'])->name('auto.update.position.testimonials')->middleware(['websiteplan']);

    //Admin Order
    Route::middleware(['websitepos'])->group(function () {
        Route::controller(OrderController::class)->prefix('/order')->group(function () {
            // Route::get('/', 'index')->name('order')->middleware(['checkSms']);

            Route::get('/', 'index')->name('order');
            Route::get('/create', 'createorder')->name('order.create');
            Route::get('/view/{id}', 'view')->name('order.view');
            Route::get('/typefilter', 'typefilter')->name('order.typefilter');
            Route::post('/changestatus', 'changestatus')->name('order.changestatus');
            Route::get('/filterstatus', 'filterstatus')->name('order.filterstatus');
            Route::get('/retypefilter', 'retypefilter')->name('order.retypefilter');
            Route::get('/restock/{id}', 'restock')->name('order.restock');
            Route::post('/details-update', 'orderDetailsUpdate')->name('order.details.update');
            Route::post('/address-update', 'orderAddressUpdate')->name('order.address.update');

            Route::post('/orders/update-comment/{order}', 'updateComment')->name('order.comment.update');

        });
    });

    Route::post('/assign/staff/order', [OrderController::class, 'assignStaffOrder'])->name('assign.staff.order');

    Route::get('/returned', [OrderController::class, 'returned'])->name('returned')->middleware(['websitepos']);
    //Route::get('/returned', [OrderController::class, 'returned'])->name('admin.returned');
    Route::get('/cancelled', [OrderController::class, 'cancelled'])->name('admin.cancelled');
    //Route::get('/orderexport', [OrderController::class, 'exportorder'])->name('admin.order.export');
    Route::get('/order/restock/{id}', [OrderController::class, 'restock'])->name('order.restock');
    Route::get('/check/courier/status/{phone}', [OrderController::class, 'checkCourierStatus'])->name("checkCourierData")->middleware(['websiteplan']);
    ;
    // End Admin Order

    //Setting
    Route::get('/settings', [SettingController::class, 'setting'])->name('setting')->middleware(['websiteplan']);
    Route::get('/update/default-shipping-area', [SettingController::class, 'updateDefaultShippingArea'])->name('updateDefaultShippingArea')->middleware(['websiteplan']);
    Route::get('/update/setting-data', [SettingController::class, 'updateSettingData'])->name('updateSettingData')->middleware(['websiteplan']);
    Route::post('/settings/updatesetting', [SettingController::class, 'updatesetting'])->name('updatesetting')->middleware(['websiteplan']);
    Route::get('/profile', [SettingController::class, 'profile'])->name('profile')->middleware(['websiteplan']);
    Route::post('/profile/update', [SettingController::class, 'updateprofile'])->name('updateprofile')->middleware(['websiteplan']);
    Route::get('/staff/profile', [SettingController::class, 'staffprofile'])->name('staff.profile')->middleware(['websiteplan']);
    Route::post('/staff/profile/update', [SettingController::class, 'updatestaffprofile'])->name('updatestaffprofile')->middleware(['websiteplan']);

    Route::post('/update/order/sms/template', [SettingController::class, 'storeOrderSMSTemplate'])->name('store.order.sms.template')->middleware(['websiteplan', 'isModulusAccess:119']);

    Route::post('/update/payment-method-text', [SettingController::class, 'updatePaymentMethodText'])->name('savePaymentMethodText')->middleware(['websiteplan']);

    // modulus
    Route::get('/modulus-list', [ModulusController::class, 'index'])->name('modulus')->middleware(['websiteplan']);
    Route::get('/modulus/change-status', [ModulusController::class, 'changeStatus'])->name('modulus.change.status')->middleware(['websiteplan']);
    Route::get('/modulus/config/{id}', [ModulusController::class, 'modulusConfig'])->name('modulus.config')->middleware(['websiteplan']);

    Route::post('/modulus/buy', [ModulusController::class, 'buy'])->name('modulus.but')->middleware(['websiteplan']);
    Route::post('/modulus/payment', [ModulusController::class, 'modulusPayment'])->name('modulus.payment')->middleware(['websiteplan']);

    Route::get('/marketing/modulus-list', [ModulusController::class, 'marketingModulusList'])->name('marketing.modulus')->middleware(['websiteplan']);

    //Domain
    Route::get('/domain', [SettingController::class, 'domain'])->name('domain')->middleware(['websiteplan']);
    Route::post('/domain/save', [SettingController::class, 'savedomain'])->name('savedomain')->middleware(['websiteplan']);
    Route::get('/changedomain', [SettingController::class, 'changedomain'])->name('auto.changedomain')->middleware(['websiteplan']);

    //Report
    Route::get('/report', [ReportController::class, 'index'])->name('report')->middleware(['websiteplan']);
    Route::get('/pos-report', [ReportController::class, 'posReport'])->name('posReport')->middleware(['websiteplan']);

    Route::get('/get/report-data/{section}', [ReportController::class, 'adminReport'])->name('report.section')->middleware(['websiteplan']);
    // routes/web.php or routes/api.php
    Route::get('/reports/revenue', [ReportController::class, 'revenueReport'])->name('report.revenueReport')->middleware(['websiteplan']);
    Route::get('/reports/pos-revenue', [ReportController::class, 'posRevenueReport'])->name('report.posRevenueReport')->middleware(['websiteplan']);
    Route::get('/reports/product-transfer-report', [ReportController::class, 'productTransferReport'])->name('report.productTransferReport')->middleware(['websiteplan']);

    Route::post('/expenses/store', [ExpenseController::class, 'store'])->name('expenses.save')->middleware(['websiteplan']);
    Route::get('/expenses/ajax', [ExpenseController::class, 'ajaxIndex'])->name('expenses.list')->middleware(['websiteplan']);
    Route::delete('/expenses/{expense}', [ExpenseController::class, 'destroy'])->name('get.expenses.list')->middleware(['websiteplan']);
    Route::get('/expenses/categories-list', [ExpenseController::class, 'getExpanseCategory'])->name('expenses.category.list')->middleware(['websiteplan']);
    Route::post('/expenses/categories', [ExpenseController::class, 'storeExpanseCategory'])->name('save.expenses.category')->middleware(['websiteplan']);
    Route::delete('/delete/expenses/categories/{id}', [ExpenseController::class, 'deleteExpanseCategory'])->name('delete.expenses.category')->middleware(['websiteplan']);


    //Review
    Route::get('/review', [ReportController::class, 'review'])->name('review')->middleware(['websiteplan']);
    Route::get('/review/delete/{id}', [ReportController::class, 'delreview'])->name('review.delete')->middleware(['websiteplan']);

    //Design Settings\\
    // Route::get('/design/settings',[DesignController::class,'designsettings'])->name('design.settings');
    // Route::get('/invoice12',[OrderController::class,'invoice'])->name('invoice');

    Route::post('/changes_design', [DesignController::class, 'changes_design'])->name('auto.changes.design')->middleware(['websiteplan']);

    Route::get('/changeslider', [DesignController::class, 'changeslider'])->name('auto.changeslider')->middleware(['websiteplan']);
    Route::get('/changebanner', [DesignController::class, 'changebanner'])->name('auto.changebanner')->middleware(['websiteplan']);
    Route::get('/changebanner-bottom', [DesignController::class, 'changebannerBottom'])->name('auto.changebanner.bottom')->middleware(['websiteplan']);
    Route::get('/changefcat', [DesignController::class, 'changefcat'])->name('auto.changefcat')->middleware(['websiteplan']);
    Route::get('/changeproduct', [DesignController::class, 'changeproduct'])->name('auto.changeproduct')->middleware(['websiteplan']);
    Route::get('/changefpro', [DesignController::class, 'changefpro'])->name('auto.changefpro')->middleware(['websiteplan']);
    Route::get('/changebsp', [DesignController::class, 'changebsp'])->name('auto.changebsp')->middleware(['websiteplan']);
    Route::get('/changenap', [DesignController::class, 'changenap'])->name('auto.changenap')->middleware(['websiteplan']);
    Route::get('/changetesti', [DesignController::class, 'changetesti'])->name('auto.changetesti')->middleware(['websiteplan']);
    Route::get('/changefooter', [DesignController::class, 'changefooter'])->name('auto.changefooter')->middleware(['websiteplan']);
    Route::get('/changeheader', [DesignController::class, 'changeheader'])->name('auto.changeheader')->middleware(['websiteplan']);

    Route::get('/changeinvoice', [LayoutController::class, 'changeinvoice'])->name('auto.changeinvoice')->middleware(['websiteplan']);

    //Pricing
    Route::get('/pricing/list', [StoreController::class, 'pricinglist'])->name('auto.pricing.list')->middleware(['websiteplan']);

    //Inventory
    Route::get('/inventory', [ProductController::class, 'inventory'])->name('inventory')->middleware(['websitepos']);

    Route::post('/inventory', [ProductController::class, 'inventory'])->name('expiry.product.filter')->middleware(['websitepos', 'isModulusAccess:118']);

    Route::post('/set-affiliate-min-withdraw', [ProductAffiliateController::class, 'setMinWithDrawAmount'])->name("affiliateMinWithdraw");

    Route::get('/product/{id}/view', [ProductController::class, 'viewproduct'])->name('product.view')->middleware(['websitepos']);
    Route::get('/stock_alert', [ProductController::class, 'stockalert'])->name('stockalert')->middleware(['websitepos']);
    Route::post('/stock-alert-qty', [ProductController::class, 'stockOutQtyStore'])->name('stockOutQty')->middleware(['websitepos']);
    Route::get('/stock_out', [ProductController::class, 'stockout'])->name('stockout')->middleware(['websitepos']);
    Route::get('/top-selling', [ReportController::class, 'topselling'])->name('topselling')->middleware(['websitepos']);
    Route::get('/lowest-selling', [ReportController::class, 'lowestselling'])->name('lowestselling')->middleware(['websitepos']);
    Route::get('/rejectorder', [ReportController::class, 'rejectorder'])->name('rejectorder')->middleware(['websitepos']);
    Route::get('/completeorder', [ReportController::class, 'completeorder'])->name('completeorder')->middleware(['websitepos']);

    //Addons
    Route::get('/addonss', [ChooseplanController::class, 'addons'])->name('addonss');
    Route::post('/savemobileappsinfo/{id}', [ChooseplanController::class, 'savemobileappsinfo'])->name('savemobileappsinfo');

    Route::get('plancheck', [ChooseplanController::class, 'plancheck'])->name('auto.plancheck')->middleware(['websiteplan']);
    Route::get('/plancheckout', [ChooseplanController::class, 'plancheckout'])->name('auto.plancheckout')->middleware(['websiteplan']);

    Route::get('addonsadd', [ChooseplanController::class, 'addonsadd'])->name('auto.addonsadd');
    Route::get('/addonsremove', [ChooseplanController::class, 'addonsremove'])->name('auto.addonsremove');
    Route::get('activityaddonsadd', [ChooseplanController::class, 'activityaddonsadd'])->name('auto.activityaddonsadd');
    Route::get('/activityaddonsremove', [ChooseplanController::class, 'activityaddonsremove'])->name('auto.activityaddonsremove');
    Route::post('/changeproduct/status', [ProductController::class, 'changeproductstatus'])->name('changeproductstatus')->middleware(['websiteplan']);
    Route::post('/changecategory/status', [CategoryController::class, 'changecategorystatus'])->name('changecategorystatus')->middleware(['websiteplan']);
    Route::post('/changebrand/status', [BrandController::class, 'changebrandstatus'])->name('changebrandstatus')->middleware(['websiteplan']);
    Route::post('/changesupplier/status', [SupplierController::class, 'changesupplierstatus'])->name('changesupplierstatus')->middleware(['websiteplan']);
    Route::post('/changecoupon/status', [PromotionController::class, 'changecouponsstatus'])->name('changecouponstatus')->middleware(['websiteplan']);
    Route::post('/changecampaign/status', [PromotionController::class, 'changecampaignssstatus'])->name('changecampaignssstatus')->middleware(['websiteplan']);
    Route::post('/changeslider/status', [DesignController::class, 'changesliderssstatus'])->name('changesliderssstatus')->middleware(['websiteplan']);
    Route::post('/changebanner/status', [DesignController::class, 'changebannerssstatus'])->name('changebannerssstatus')->middleware(['websiteplan']);
    Route::post('/changetestimonial/status', [TestimonialsController::class, 'changetestimonialssstatus'])->name('changetestimonialssstatus')->middleware(['websiteplan']);
    Route::post('/changepage/status', [PageController::class, 'changepagessstatus'])->name('changepagessstatus')->middleware(['websiteplan']);
    Route::post('/changecustomer/status', [CustomerController::class, 'changecustomerssstatus'])->name('changecustomerssstatus')->middleware(['websiteplan']);
    Route::post('/changereview/status', [ReportController::class, 'changereviewssstatus'])->name('changereviewssstatus')->middleware(['websiteplan']);
    Route::post('/changestaff/status', [StaffController::class, 'changestaffssstatus'])->name('changestaffssstatus')->middleware(['websiteplan']);
    Route::post('/changerole/status', [RolepermissionController::class, 'changerolessstatus'])->name('changerolessstatus')->middleware(['websiteplan']);
    Route::post('/changebranch/status', [BranchController::class, 'changebranchssstatus'])->name('changebranchssstatus')->middleware(['websiteplan']);

    //Activity Log
    Route::get('/activitylog', [ActivityLogController::class, 'index'])->name('activitylog')->middleware(['websiteplan']);
    Route::post('/activitylog/delete', [ActivityLogController::class, 'deleteall'])->name('deleteactivitylog')->middleware(['websiteplan']);
    Route::get('/activitylog/filter', [ActivityLogController::class, 'datefilter'])->name('activitydatefilter')->middleware(['websiteplan']);
    Route::get('/admin/view/notification', [OrderController::class, 'viewnotifi'])->name('view.notification')->middleware(['websiteplan']);


    Route::get('/design/theme/filter', [ThemeController::class, 'themesearch'])->name('searchtheme')->middleware(['websiteplan']);
    Route::get('/design/invoice/active/{id}', [InvoiceController::class, 'activeinvoice'])->name('invoice.active')->middleware(['websiteplan']);
    Route::post('/buyinvoice', [InvoiceController::class, 'buyinvoice'])->name('buyinvoice')->middleware(['websiteplan']);

    Route::get('/themecustomize', [ThemeController::class, 'themecustomize'])->name('themecustomize')->middleware(['websiteplan']);
    Route::get('/addons-pack', [AddonsApiController::class, 'addonPack'])->name('addon.pack')->middleware(['websiteplan']);
    Route::get('/addons-pack-pages', [AddonsApiController::class, 'addonPackPaginate'])->name('auto.addons.pack.pages')->middleware(['websiteplan']);
    Route::post('/addons-pack', [AddonsApiController::class, 'addonPackStore'])->name('addon.pack.store')->middleware(['websiteplan']);
    Route::post('/addons-pack/edit', [AddonsApiController::class, 'addonPackUpdate'])->name('addon.pack.update')->middleware(['websiteplan']);
    Route::get('/addons-pack/delete', [AddonsApiController::class, 'addonPackDelete'])->name('addon.pack.delete')->middleware(['websiteplan']);
    Route::post('/savecustomizinfo', [ThemeController::class, 'savecustomizinfo'])->name('savecustomizinfo')->middleware(['websiteplan']);
    Route::post('/dmin/sendmessage/token/{token}', [ThemeController::class, 'sendmessagetoken'])->name('sendmessage.token')->middleware(['websiteplan']);
    Route::get('/removefromofr/{id}', [PromotionController::class, 'removefromofrsss'])->name('removefromofr')->middleware(['websiteplan']);

    Route::get('/websitesetup', [ThemeController::class, 'websitesetup'])->name('websitesetup')->middleware(['websiteplan']);
    Route::get('/paymentgateway', [ThemeController::class, 'paymentgateway'])->name('paymentgateway')->middleware(['websiteplan']);
    Route::post('/savepaymentinfo', [ThemeController::class, 'savepaymentinfo'])->name('savepaymentinfo')->middleware(['websiteplan']);

    Route::post('/websitesetup/save/product', [ThemeController::class, 'websitesetupSaveProduct'])->name('websitesetup.save.product')->middleware(['websiteplan']);
    Route::post('/websitesetup/upload/product', [ThemeController::class, 'websitesetupUploadProduct'])->name('websitesetup.upload.product')->middleware(['websiteplan']);
    Route::get('/websitesetup/save/product/delete/{id}', [ThemeController::class, 'websitesetupProductDelete'])->name('websitesetup.delete.product')->middleware(['websiteplan']);
    Route::post('/websitesetup/save/setup-details', [ThemeController::class, 'websitesetupSaveSetupDetails'])->name('websitesetup.save.setup.details')->middleware(['websiteplan']);
    Route::delete('/websitesetup/delete/product-image/{id}', [ThemeController::class, 'deleteWebsiteProductImage'])->name('delete.website.setup.image')->middleware(['websiteplan']);

    //Digital Marketing
    Route::get('/digital_marketing', [DigitalMarketingController::class, 'index'])->name('digital_marketing')->middleware(['digitalplan']);
    Route::get('/content_download', [DigitalMarketingController::class, 'content_download'])->name('content_download')->middleware(['digitalplan']);
    Route::post('/content/file/download', [DigitalMarketingController::class, 'contentFileDownload'])->name('content.file.download')->middleware(['digitalplan']);
    Route::get('/content/file/view/{id}', [DigitalMarketingController::class, 'contentFileView'])->name('content.file.view')->middleware(['digitalplan']);
    Route::get('/content_correction', [DigitalMarketingController::class, 'content_correction'])->name('content_correction')->middleware(['digitalplan']);
    Route::get('/boosting', [DigitalMarketingController::class, 'boosting'])->name('boosting')->middleware(['digitalplan']);
    Route::post('/submitboosting', [DigitalMarketingController::class, 'submitboosting'])->name('submitboosting')->middleware(['digitalplan']);

    // hk
    Route::get('/digital-marketing/required-information', [DigitalMarketingController::class, 'requiredInformation'])->name('required.information')->middleware(['digitalplan']);

    // Required Information From for ditital marketing
    Route::post('/digital-marketing/required-information', [RequiredInformationController::class, 'store'])->name('required.information.store')->middleware(['digitalplan']);
    Route::post('/digital-marketing/required-information/individual-content', [RequiredInformationController::class, 'individualContentStore'])->name('required.information.individual.content.store')->middleware(['digitalplan']);
    Route::get('/digital-marketing/required-information/individual-content/delete/{id}', [RequiredInformationController::class, 'individualContentDelete'])->name('required.information.individual.content.delete')->middleware(['digitalplan']);
    // hk end

    Route::get('/content/download/{id}', [DigitalMarketingController::class, 'downloadcontent'])->name('auto.content.download.id');
    //Message
    Route::get('messages', [ChatsController::class, 'fetchMessages'])->name('auto.messages');
    Route::post('messages', [ChatsController::class, 'sendMessage'])->name('auto.messages.2');
    //Email
    Route::get('/webmails', [WebmailController::class, 'webmail'])->name('emaillist');
    Route::get('/webemails/delete/{email}', [WebmailController::class, 'webmaildelete'])->name('emaillistdelete');
    Route::post('/createwebemail', [WebmailController::class, 'createwebemail'])->name('createwebemail');
    Route::post('/changewebmailpassword', [WebmailController::class, 'changewebmailpassword'])->name('changewebmailpassword');
    Route::get('/acc', [WebmailController::class, 'acc'])->name('acc');

    Route::get('quick-login-info', [QuickLoginController::class, 'quickLoginInfo'])->name('quick.login.info');
    Route::post('quick-login-info', [QuickLoginController::class, 'quickLoginInfoStore'])->name('quick.login.info.store');

    Route::group(['middleware' => ['isModulusAccess:117']], function () {
        // announcement
        Route::get('/announcement/{id?}', [AnnouncementController::class, 'index'])->name('announcement.index');
        Route::post('/announcement/store', [AnnouncementController::class, 'store'])->name('announcement.store');
        Route::post('/announcement-status', [AnnouncementController::class, 'changeAnnouncementStatus'])->name('announcement.status.change');
        Route::get('/single-announcement-status', [AnnouncementController::class, 'singleAnnouncementStatusChange'])->name('single.announcement.status.change');
        Route::post('/delete-announcement', [AnnouncementController::class, 'deleteAnnouncement'])->name('delete.announcement');
    });

    Route::get('/product/affiliate/withdraw-reject/{id}', [ProductAffiliateController::class, 'rejectWithdraw'])->name('reject.product.affiliate.withdraw');
    Route::post('/product/affiliate/withdraw-approved', [ProductAffiliateController::class, 'approvedWithdraw'])->name('approved.product.affiliate.withdraw');

    Route::get('/domain/connect/request/domain/{id}', [SuperAdminController::class, 'domainConnectRequest'])->name('domain.connect.request');

    Route::post('/amarpay/credentials/store', [MarchantPaymentGetwayKYCController::class, 'amarpayCredentials'])->name('store.amarpayKYC');

    Route::get('/amarpay/order-list', [AmarpayPaymentController::class, 'amarPayOrderList'])->name('payment.order.list');
    Route::get('/amarpay/payment-withdraw-list', [AmarpayPaymentController::class, 'amarPayPaymentWithdrawList'])->name('payment.withdraw.list');
    Route::post('/amarpay/payment-withdraw-request', [AmarpayPaymentController::class, 'amarPayPaymentWithdrawRequest'])->name('amarpay.payment.withdraw.request');


    /* Abandoned Cart Start */
    Route::get('/abandoned/cart/list', [AbandonedCartController::class, 'abandonedCartList'])->name('abandoned.cart.list');
    Route::get('/abandoned/cart/cart-list/{id}', [AbandonedCartController::class, 'abandonedCartItemList'])->name('abandoned.cart.item.list');

    /* Abandoned Cart End */

});

Route::post('/changelang', [AdminController::class, 'changelang'])->name('admin.changelang');

// UniSharp Laravel File manager
Route::group(['prefix' => 'laravel-filemanager', 'as' => 'laravel-filemanager.', 'middleware' => ['web', 'auth']], function () {
    Route::post('/custom-upload', [\App\Http\Controllers\FileManager\FilemanagerController::class, 'upload'])->name('custom.lfm.upload');

    \UniSharp\LaravelFilemanager\Lfm::routes();
});


Route::middleware(['auth'])->group(function () {
    Route::get('/notification-list', [AdminNotificationController::class, 'notificationList'])->name('notification.notification.list');
    Route::get('/view-notification/{id}', [AdminNotificationController::class, 'viewNotification'])->name('notification.view-notification');
});

Route::middleware(['auth', 'otpverify', 'admin'])->group(function () {
    // Registration Fee Payment View
    Route::get('/registration/fee-payment-method', [AdminController::class, 'showRegistrationPaymentMethod'])->name('showRegistrationPaymentMethod');
    Route::post('/registration/fee-payment-method', [AdminController::class, 'registrationFeePayment'])->name('registrationFeePayment');
});

//product qr code, export
Route::get('/download-qr-code/{id}', [QrCodeController::class, 'downloadQrCode'])->name('download.qrcode');

Route::get('/admin/products/export-selected-filtered-excel', [ProductController::class, 'exportSelectedOrFilteredExcel'])
    ->name('admin.product.export.excel');

Route::get('/invoice/1', [InvoiceController::class, 'invoice1']);
Route::post('admin/login', [AdminLoginController::class, 'adminlogin'])->name('mslogin');
Route::get('/updateattribute', [ProductController::class, 'updateattribute']);
Route::get('/deleteattribute', [ProductController::class, 'deleteattribute']);
Route::get('/updatesizeattribute', [ProductController::class, 'updatesizeattribute']);
Route::get('/deletesizeattribute', [ProductController::class, 'deletesizeattribute']);
Route::get('/updateonlycolorattribute', [ProductController::class, 'updateonlycolorattribute']);
Route::get('/deleteonlycolorattribute', [ProductController::class, 'deleteonlycolorattribute']);

Route::get('/products/searching/', [ProductController::class, 'productSearch'])->name('product.searching');

Route::get('/pay-noti', [SuperAdminController::class, 'payNoti']);
Route::get('/pay-noti/by-customer/{id}', [SuperAdminController::class, 'payNotiByCustomer'])->name('superAdmin.payNotiByCustomer');
Route::post('/send/custom/sms', [SuperAdminController::class, 'sendCustomPaySms'])->name('superAdmin.sendCustomPaySms');
Route::post('/send/multi-pay/sms', [SuperAdminController::class, 'sendMultiplePaySms'])->name('superAdmin.sendMultiplePaySms');
Route::get('/make-call/by-customer/{id}', [SuperAdminController::class, 'changeCustomerCallStatus'])->name('superAdmin.changeCallStatus');

Route::post('/save/whatsapp/custom-message', [SuperAdminController::class, 'saveWhatsAppMessage'])->name('superadmin.saveWhatsAppMessage');

Route::get('/getmessage', [SuperAdminController::class, 'getmessage']);
Route::post('/sendmessage', [SuperAdminController::class, 'sendmessage']);
Route::post('/sendmessageadmin', [SuperAdminController::class, 'sendmessageadmin']);
Route::get('/store', [StoreController::class, 'store'])->name('store.list');
Route::post('/savestore', [StoreController::class, 'save'])->name('savestore');
Route::get('/choose-your-prodcuts', function () {
    return redirect()->route('store.list');
})->name('ChooseProducts');
//Route::get('/choose-your-prodcuts', [StoreController::class, 'ChooseProducts'])->name('ChooseProducts');
Route::post('/choose-your-prodcuts', [StoreController::class, 'ChooseProductsInfoSubmit'])->name('ChooseProductsInfoSubmit');

Route::get('/activestore/{id}', [StoreController::class, 'activestore'])->name('activestore');
Route::post('/saveappslink', [SuperAdminController::class, 'saveappslink'])->name('saveappslink');

Route::get('/invoice/view/{id}', [PosController::class, 'invoiceview'])->name('admin.invoiceview');

Route::get('/getnotiorder', [OrderController::class, 'getnotiorder']);
Route::get('/updateunitattribute', [ProductController::class, 'updateunitattribute']);
Route::get('/deleteunitattribute', [ProductController::class, 'deleteunitattribute']);
Route::get('/deactivestore', [StoreController::class, 'deactivestore'])->name('admin.deactivestore');


Route::get('/staff/web-set-up', [StaffController::class, 'webSetUp'])->name('staff.webSetUp');
Route::post('/staff/web-set-up', [StaffController::class, 'webSetUpLogin'])->name('staff.webSetUpLogin');
Route::get('/staff/web-set-up/work-assign', [StaffController::class, 'workAssign'])->name('staff.workAssign');
Route::post('/staff/web-set-up/work-assign', [StaffController::class, 'workAssignStore'])->name('staff.workAssign.store');
Route::get('/staff/web-set-up/view/details/{id}', [StaffController::class, 'viewSetupDate'])->name('staff.view.setup.data');
Route::post('/staff/web-set-up/save/details', [StaffController::class, 'staffWebsitesetupSaveSetupDetails'])->name('staff.websitesetup.save.setup.details');
Route::post('/staff/web-set-up/save/product', [StaffController::class, 'staffWebsitesetupSaveProduct'])->name('staff.websitesetup.save.product');
Route::get('/staff/web-set-up/delete/product/{id}', [StaffController::class, 'staffWebsitesetupDeleteProduct'])->name('staff.websitesetup.delete.product');
Route::get('/staff/web-set-up/view/product/{id}', [StaffController::class, 'staffWebsitesetupViewProduct'])->name('staff.websitesetup.view.product');
Route::post('/staff/web-set-up/update/product', [StaffController::class, 'staffWebsitesetupUpdateProduct'])->name('staff.websitesetup.update.product');
Route::get('/staff/web-set-up/run/product-create/{store}', [StaffController::class, 'runProductCreate'])->name('staff.websitesetup.run.product.create');
Route::get('/staff/web-set-up/upload-status-complete/{store}', [StaffController::class, 'uploadStatusComplete'])->name('staff.websitesetup.upload.complete');

Route::middleware(['auth', 'superadmin'])->group(function () {

    Route::get('/superadmin/affiliate-marketing', [SuperAdminController::class, 'affiliateMarketing'])->name('superadmin.affiliateMarketing');

    //Supser Promotion Coupon
    Route::get('superadmin/promotions/coupon', [AdminCouponController::class, 'coupon'])->name('superadmin.promotion.coupon');
    Route::post('superadmin/promotions/coupon/save', [AdminCouponController::class, 'couponsave'])->name('superadmin.savecoupon');
    Route::get('superadmin/promotions/coupon/{id}/edit', [AdminCouponController::class, 'editcoupon'])->name('superadmin.coupon.edit');
    Route::post('superadmin/promotions/coupon/update/{id}', [AdminCouponController::class, 'updatecoupon'])->name('superadmin.coupon.update');
    Route::get('superadmin/promotions/coupon/delete/{id}', [AdminCouponController::class, 'deletecoupon'])->name('superadmin.coupon.delete');
    Route::get('superadmin/changecouponstatus', [AdminCouponController::class, 'changecouponstatus'])->name('superadmin.changecouponstatus');

    Route::get('/superadmin/dashboard', [SuperAdminController::class, 'index'])->name('superadmin.index');
    Route::get('/superadmin/customer', [SuperAdminController::class, 'customer'])->name('superadmin.customer');
    Route::get('/clients', [SuperAdminController::class, 'clientlist'])->name('admin.clients');
    Route::post('/assign/seller-to-customer', [SuperAdminController::class, 'assignSeller'])->name('assign.client.inSeller');
    Route::post('/make/my-seller', [SuperAdminController::class, 'makeMySeller'])->name('admin.client.seller.status');
    Route::get('/paid-clients', [SuperAdminController::class, 'padiClients'])->name('admin.paidClients');
    Route::get('/register-clients', [SuperAdminController::class, 'registerClients'])->name('admin.registerClients');
    Route::get('/paid/client/list', [SuperAdminController::class, 'padiClientsList'])->name('paidClientsList');
    Route::get('/landing-page/client-list', [SuperAdminController::class, 'landingPageClientsList'])->name('landingPageClientsList');
    Route::get('/addon/sell/report', [SuperAdminController::class, 'addonSellReport'])->name('addonSellReport');
    Route::get('/clients-search', [SuperAdminController::class, 'clientlistSearch'])->name('admin.clients.search');
    Route::post('/clients-search-by-follow-up', [SuperAdminController::class, 'clientlistSearchByfollowUpDate'])->name('admin.clients.followUpDate');

    // Route::get('/super-admin/clients', [SuperAdminClientController::class, 'index'])->name('superAdmin.clients');
    // Route::get('/super-admin/clients/modal', [SuperAdminClientController::class, 'modalOpen'])->name('superAdmin.clients.modal');

    Route::get('/clients-activities', [SuperAdminController::class, 'clientActivities'])->name('admin.clients.activities');
    /*Route::get('/get-clients-activities', [SuperAdminController::class, 'getClientActivitiesData'])->name('super_admin.clients.activities.data');*/
    Route::get('/clients-activities/data', [SuperAdminController::class, 'getClientActivitiesData'])->name('admin.clients.activities.data');

    Route::get('/clients-follow-up', [SuperAdminController::class, 'clientActivitiesFollowUp'])->name('admin.clients.followUp');
    Route::get('/clients-follow-up/search', [SuperAdminController::class, 'clientActivitiesFollowUpSearch'])->name('admin.clients.followUp.search');
    // Route::get('/clients-activities', [SuperAdminController::class, 'clientActivities'])->name('admin.clients.activities');
    Route::post('/clients-activities/by-date', [SuperAdminController::class, 'clientActivitiesByDate'])->name('superadmin.clients.activities.byDate');
    Route::get('/clients-activities/by-date', [SuperAdminController::class, 'clientActivities'])->name('superadmin.clients.activities.byDate.index');
    Route::post('/clients-activities/comments', [SuperAdminController::class, 'clientActivitiesComments'])->name('superadmin.clients.activities.comments');
    Route::post('/client/comment', [SuperAdminController::class, 'storeComment'])->name('admin.client.commnet');
    Route::post('/referral-commission', [SuperAdminController::class, 'referralCommissionUpdate'])->name('superadmin.update.referral_commission');
    Route::get('/clients/filter', [SuperAdminController::class, 'clientdatefilter'])->name('superadmin.clientlistdatefilter');
    Route::post('/deleteclient/', [SuperAdminController::class, 'deleteclient'])->name('admin.deleteclient');

    Route::get('/planorder', [SuperAdminController::class, 'planorderlist'])->name('admin.planorder');
    Route::get('/superadmin/plandatefilter', [SuperAdminController::class, 'productdatefilter'])->name('superadmin.plandatefilter');
    Route::get('/superadmin/planinvoice/{id}', [SuperAdminController::class, 'planinvoice'])->name('superadmin.planinvoice');
    Route::get('/domain/list', [SuperAdminController::class, 'domainlist'])->name('superadmin.domainlist');
    Route::get('/delete/domain/list', [SuperAdminController::class, 'deleteDomainList'])->name('superadmin.deleteDomainList');
    Route::get('/buy/domain/list', [SuperAdminController::class, 'domainBuyingList'])->name('buy.domain.list');
    Route::get('/domain/request', [SuperAdminController::class, 'domainrequest'])->name('superadmin.domainrequest');
    Route::get('/domain/request/{id}', [SuperAdminController::class, 'domainrequestaccept'])->name('superadmin.domainrequest.accept');
    Route::get('/domain/request/connect/domain/{id}', [SuperAdminController::class, 'domainConnectRequest'])->name('superadmin.domainrequest.connect.domain');
    Route::get('/domain/request/processing/{id}', [SuperAdminController::class, 'domainrequestprocessing'])->name('superadmin.domainrequest.processing');
    Route::get('/domain/buy/request/{id}', [SuperAdminController::class, 'domainBuyRequest'])->name('superadmin.domainrequest.buy.domain');
    Route::get('/domain/request/reject/{id}', [SuperAdminController::class, 'domainrequestreject'])->name('superadmin.domainrequest.reject');
    Route::get('/domain/delete/{id}', [SuperAdminController::class, 'deletedomain'])->name('superadmin.domain.delete');
    Route::get('/domain/cpanel/delete/{id}', [SuperAdminController::class, 'deleteDomainFromCpanel'])->name('superadmin.domain.cpanel.delete');
    Route::get('/design/list', [SuperAdminController::class, 'designlist'])->name('superadmin.designlist');
    Route::get('/design/create', [SuperAdminController::class, 'designcreate'])->name('superadmin.design.create');
    Route::post('/design/save', [SuperAdminController::class, 'designsave'])->name('superadmin.design.save');
    Route::get('/changedesignstatus', [SuperAdminController::class, 'changedesignstatus'])->name('changedesignstatus');
    Route::get('/changetemplatestatus', [SuperAdminController::class, 'changetemplatestatus'])->name('changetemplatestatus');
    Route::get('/design/edit/{id}', [SuperAdminController::class, 'editdesign'])->name('superadmin.editdesign');
    Route::post('/design/update/{id}', [SuperAdminController::class, 'updatedesign'])->name('superadmin.designupdate');
    Route::get('/design/delete/{id}', [SuperAdminController::class, 'deletedesign'])->name('superadmin.deletedesign');
    Route::get('/design/list/typefilter', [SuperAdminController::class, 'designtypefilter'])->name('superadmin.design.typefilter');

    Route::get('/business-category', [SuperAdminController::class, 'business_category'])->name('super_admin.business_category');
    Route::post('/business-category', [SuperAdminController::class, 'business_category_store'])->name('super_admin.business_category.store');
    Route::put('/business-category/{id}', [SuperAdminController::class, 'business_category_update'])->name('super_admin.business_category.update');

    Route::get('/template/list', [SuperAdminController::class, 'templates'])->name('superadmin.template');
    Route::get('/template/create', [SuperAdminController::class, 'createtemplate'])->name('superadmin.template.create');
    Route::post('/template/save', [SuperAdminController::class, 'savetemplate'])->name('superadmin.template.save');
    Route::get('/template/edit/{id}', [SuperAdminController::class, 'edittemplate'])->name('superadmin.template.edit');
    Route::post('/template/update/{id}', [SuperAdminController::class, 'updatetemplate'])->name('superadmin.template.update');
    Route::get('/template/delete/{id}', [SuperAdminController::class, 'deletetemplate'])->name('superadmin.template.delete');
    Route::get('/update-position-template', [SuperAdminController::class, 'updatepositiontemplate']);

    /***** Store demo data start ******/
    /***** Store demo data category start ******/
    Route::get('/store/default/category-list', [StoreDemoDataController::class, 'storeCategoryList'])->name('superadmin.store.category.list');
    Route::get('/store/default/category-create', [StoreDemoDataController::class, 'storeCategoryCreate'])->name('superadmin.store.category.create');
    Route::post('/store/default/category-store', [StoreDemoDataController::class, 'storeCategorySave'])->name('superadmin.store.category.save');
    Route::get('/store/default/category-edit/{id}', [StoreDemoDataController::class, 'storeCategoryEdit'])->name('superadmin.store.edit.category');
    Route::post('/store/default/category-update', [StoreDemoDataController::class, 'storeCategoryUpdate'])->name('superadmin.store.update.category');
    Route::get('/store/default/category-delete/{id}', [StoreDemoDataController::class, 'storeCategoryDelete'])->name('superadmin.store.delete.category');
    /***** Store demo data category end ******/

    /***** Store demo data product start ******/
    Route::get('/store/default/product-list', [StoreDemoDataController::class, 'storeProductList'])->name('superadmin.store.product.list');
    Route::get('/store/default/product-create', [StoreDemoDataController::class, 'storeProductCreate'])->name('superadmin.store.product.create');
    Route::post('/store/default/product-store', [StoreDemoDataController::class, 'storeProductSave'])->name('superadmin.store.product.save');
    Route::get('/store/default/product-edit/{id}', [StoreDemoDataController::class, 'storeProductEdit'])->name('superadmin.store.edit.product');
    Route::post('/store/default/product-update', [StoreDemoDataController::class, 'storeProductUpdate'])->name('superadmin.store.update.product');
    Route::get('/store/default/product-delete/{id}', [StoreDemoDataController::class, 'storeProductDelete'])->name('superadmin.store.delete.product');
    /***** Store demo data product end ******/

    /***** Store demo data slider start ******/
    Route::get('/store/default/slider-list', [StoreDemoDataController::class, 'storeSliderList'])->name('superadmin.store.slider.list');
    Route::get('/store/default/slider-create', [StoreDemoDataController::class, 'storeSliderCreate'])->name('superadmin.store.slider.create');
    Route::post('/store/default/slider-store', [StoreDemoDataController::class, 'storeSliderSave'])->name('superadmin.store.slider.save');
    Route::get('/store/default/slider-edit/{id}', [StoreDemoDataController::class, 'storeSliderEdit'])->name('superadmin.store.edit.slider');
    Route::post('/store/default/slider-update', [StoreDemoDataController::class, 'storeSliderUpdate'])->name('superadmin.store.update.slider');
    Route::get('/store/default/slider-delete/{id}', [StoreDemoDataController::class, 'storeSliderDelete'])->name('superadmin.store.delete.slider');
    /***** Store demo data slider end ******/

    /***** Store demo data banner start ******/
    Route::get('/store/default/banner-list', [StoreDemoDataController::class, 'storeBannerList'])->name('superadmin.store.banner.list');
    Route::get('/store/default/banner-create', [StoreDemoDataController::class, 'storeBannerCreate'])->name('superadmin.store.banner.create');
    Route::post('/store/default/banner-store', [StoreDemoDataController::class, 'storeBannerSave'])->name('superadmin.store.banner.save');
    Route::get('/store/default/banner-edit/{id}', [StoreDemoDataController::class, 'storeBannerEdit'])->name('superadmin.store.edit.banner');
    Route::post('/store/default/banner-update', [StoreDemoDataController::class, 'storeBannerUpdate'])->name('superadmin.store.update.banner');
    Route::get('/store/default/banner-delete/{id}', [StoreDemoDataController::class, 'storeBannerDelete'])->name('superadmin.store.delete.banner');
    /***** Store demo data banner end ******/

    /***** Store demo data theme start ******/
    Route::get('/store/default/theme-list', [StoreDemoDataController::class, 'storeThemeList'])->name('superadmin.store.theme.list');
    Route::get('/store/default/theme-create', [StoreDemoDataController::class, 'storeThemeCreate'])->name('superadmin.store.theme.create');
    Route::post('/store/default/theme-store', [StoreDemoDataController::class, 'storeThemeSave'])->name('superadmin.store.theme.save');
    Route::get('/store/default/theme-edit/{id}', [StoreDemoDataController::class, 'storeThemeEdit'])->name('superadmin.store.edit.theme');
    Route::post('/store/default/theme-update', [StoreDemoDataController::class, 'storeThemeUpdate'])->name('superadmin.store.update.theme');
    Route::get('/store/default/theme-delete/{id}', [StoreDemoDataController::class, 'storeThemeDelete'])->name('superadmin.store.delete.theme');
    /***** Store demo data theme end ******/

    /***** Store demo data header start ******/
    Route::get('/store/default/header-list', [StoreDemoDataController::class, 'storeHeaderList'])->name('superadmin.store.header.list');
    Route::get('/store/default/header-create', [StoreDemoDataController::class, 'storeHeaderCreate'])->name('superadmin.store.header.create');
    Route::post('/store/default/header-store', [StoreDemoDataController::class, 'storeHeaderSave'])->name('superadmin.store.header.save');
    Route::get('/store/default/header-edit/{id}', [StoreDemoDataController::class, 'storeHeaderEdit'])->name('superadmin.store.edit.header');
    Route::post('/store/default/header-update', [StoreDemoDataController::class, 'storeHeaderUpdate'])->name('superadmin.store.update.header');
    Route::get('/store/default/header-delete/{id}', [StoreDemoDataController::class, 'storeHeaderDelete'])->name('superadmin.store.delete.header');
    /***** Store demo data header end ******/
    /***** Store demo data end ******/

    Route::get('/notification', [SuperAdminController::class, 'notification'])->name('notification');
    Route::get('/notification/create', [SuperAdminController::class, 'createnotification'])->name('notification.create');
    Route::post('/notification/save', [SuperAdminController::class, 'savenotification'])->name('notification.save');
    Route::get('/notification/edit/{id}', [SuperAdminController::class, 'editnotification'])->name('notification.edit');
    Route::post('/notification/update/{id}', [SuperAdminController::class, 'updatenotification'])->name('notification.update');
    Route::get('/notification/delete/{id}', [SuperAdminController::class, 'deletenotification'])->name('notification.delete');

    Route::get('/icon-pack', [SuperAdminController::class, 'iconpack'])->name('superadmin.iconpack');
    Route::get('/icon-pack/create', [SuperAdminController::class, 'createiconpack'])->name('superadmin.iconpack.create');
    Route::post('/icon-pack/save', [SuperAdminController::class, 'saveiconpack'])->name('superadmin.iconpack.save');
    Route::get('/icon-pack/delete/{id}', [SuperAdminController::class, 'deleteiconpack'])->name('superadmin.iconpack.delete');

    Route::get('/messages', [SuperAdminController::class, 'messages'])->name('messages');

    Route::get('/gsc-fb-pixel', [SuperAdminController::class, 'gscFbPixel'])->name('gscFbPixel');
    Route::get('/gsc-fb-pixel-details', [SuperAdminController::class, 'gscFbPixelDetails'])->name('gscFbPixelDetails');
    Route::get('/messages/{uid}/{store_id}', [SuperAdminController::class, 'seemessages'])->name('seemessages');

    Route::get('/client/view/{id}', [SuperAdminController::class, 'viewcustomer'])->name('customer.view');
    Route::patch('/stores/{store}/toggle-status', [SuperAdminController::class, 'toggleStatus'])->name('stores.toggle-status');

    // Client access route
    Route::post('/superadmin/staff/client/access', [SuperAdminController::class, 'staffClientAccess'])->name('superadmin.staff.client.access');
    // Client access remove route
    Route::post('/superadmin/staff/client/remove/access', [SuperAdminController::class, 'staffClientRemoveAccess'])->name('superadmin.staff.client.remove.access');

    Route::get('/superadmin/staff', [SuperAdminController::class, 'staff'])->name('superadmin.staff');
    Route::get('/superadmin/staff/create', [SuperAdminController::class, 'staffcreate'])->name('superadmin.staff.create');
    Route::post('/superadmin/staff/save', [SuperAdminController::class, 'staffsave'])->name('superadmin.staff.save');
    Route::get('/superadmin/staff/edit/{id}', [SuperAdminController::class, 'staffedit'])->name('superadmin.staff.edit');
    Route::post('/superadmin/staff/update/{id}', [SuperAdminController::class, 'staffupdate'])->name('superadmin.staff.update');
    Route::get('/superadmin/staff/delete/{id}', [SuperAdminController::class, 'staffdelete'])->name('superadmin.staff.delete');
    Route::get('/changestaffstatus', [SuperAdminController::class, 'changestaffstatus'])->name('changestaffstatus');
    Route::get('/superadmin/staff/commission/{id}', [SuperAdminController::class, 'superstaffCommission'])->name('superadmin.superstaff.commission');
    Route::get('/superadmin/staff/payment/history/{id}', [SuperAdminController::class, 'superstaffPaymentHistory'])->name('superadmin.superstaff.commission.payment.history');
    Route::post('/superadmin/staff/commission/pay', [SuperAdminController::class, 'superstaffCommissionPay'])->name('superadmin.pay.staff.commission');
    Route::post('/superadmin/staff/commission/pay-status-change', [SuperAdminController::class, 'superstaffCommissionChangePayStatus'])->name('superadmin.superstaff.commission.pay.unpay');

    Route::get('/superadmin/role/permission', [SuperAdminController::class, 'rolepermission'])->name('superadmin.role.permission');
    Route::post('/superadmin/role/permission/save', [SuperAdminController::class, 'supersaverole'])->name('superadmin.saverole');
    Route::get('/superadmin/role/permission/{id}/edit', [SuperadminController::class, 'supereditrole'])->name('superadmin.editrole');
    Route::post('/superadmin/role/permission/update/{id}', [SuperAdminController::class, 'superupdaterole'])->name('superadmin.updaterole');
    Route::get('/superadmin/role/permission/delete/{id}', [SuperAdminController::class, 'superdeleterole'])->name('superadmin.deleterole');
    Route::get('/superadmin/role/permission/{id}/permission', [SuperAdminController::class, 'superpermission'])->name('superadmin.permission');
    Route::post('/superadmin/savepermission/{id}', [SuperAdminController::class, 'supersavepermission'])->name('superadmin.savepermission');

    Route::get('/superadmin/order-plan-request/{status?}', [SuperAdminController::class, 'filterOrderPlanRequest'])->name('superadmin.orderPlanrequest');
    Route::post('/superadmin/order-plan-request/{id}/update-payment', [SuperAdminController::class, 'updateOrderPayment'])->name('superadmin.orderPlanrequest.update-payment');

    Route::get('/superadmin/modulus/request-list', [SuperAdminController::class, 'modulusRequest'])->name('superadmin.modulus.request');
    Route::get('/superadmin/planorderrequest/rejected', [SuperAdminController::class, 'planorderrequestrejected'])->name('superadmin.planorderrequest.rejected');
    Route::get('/superadmin/planorder/accept/{id}', [SuperAdminController::class, 'acceptplanorder'])->name('superadmin.planorder.accept');
    Route::get('/superadmin/plan-order/accept/{id}', [SuperAdminController::class, 'newacceptplanorder'])->name('superadmin.newacceptplanorder.accept');
    Route::get('/superadmin/planorder/reject/{id}', [SuperAdminController::class, 'rejectplanorder'])->name('superadmin.planorder.reject');
    Route::get('/superadmin/planorder/new-reject/{id}', [SuperAdminController::class, 'newRejectOrderPlan'])->name('superadmin.planorder.new.reject');
    Route::get('/superadmin/modulus/accept/{id}', [SuperAdminController::class, 'acceptmodulus'])->name('superadmin.modulus.accept');
    Route::get('/superadmin/modulus/reject/{id}', [SuperAdminController::class, 'rejectmodulus'])->name('superadmin.modulus.reject');
    Route::get('/superadmin/planorderrequest-today', [SuperAdminController::class, 'todayplanorderrequest'])->name('superadmin.todayplanorderrequest');
    Route::get('/superadmin/plan-order-view-invoice/{id}', [SuperAdminController::class, 'viewPlaneOrderInvoice'])->name('superadmin.planorder.view.invoice');
    Route::get('/superadmin/order-plan-request/accept-due/{historyId}', [SuperAdminController::class, 'acceptDuePaymentRequest'])->name('superadmin.orderPlanrequest.accept-due');

    Route::get('/superadmin/recycle-bin/product', [SuperAdminController::class, 'productrecycle'])->name('superadmin.productrecycle');
    Route::get('/superadmin/restoreproduct/{id}', [SuperAdminController::class, 'restoreproduct'])->name('restoreproduct');
    Route::get('/superadmin/recycle-bin/category', [SuperAdminController::class, 'categoryrecycle'])->name('superadmin.categoryrecycle');
    Route::get('/superadmin/categoryrestore/{id}', [SuperAdminController::class, 'categoryrestore'])->name('categoryrestore');

    Route::post('/superadmin/deleteallproduct', [SuperAdminController::class, 'deleteallproduct'])->name('superadmin.deleteallproduct');
    Route::post('/superadmin/restoreallproduct', [SuperAdminController::class, 'restoreallproduct'])->name('superadmin.restoreallproduct');

    Route::post('/superadmin/deleteallcategory', [SuperAdminController::class, 'deleteallcategory'])->name('superadmin.deleteallcategory');
    Route::post('/superadmin/restoreallcategory', [SuperAdminController::class, 'restoreallcategory'])->name('superadmin.restoreallcategory');

    Route::get('/superadmin/addons', [SuperAdminController::class, 'addonssmobileapps'])->name('superadmin.mobilapps');
    Route::get('/superadmin/addons/add', [SuperAdminController::class, 'addonsAdd'])->name('superadmin.addons.add');
    Route::post('/superadmin/addons/add', [SuperAdminController::class, 'addonsAddStore'])->name('superadmin.addons.store');
    Route::get('/superadmin/addons/update-status', [SuperAdminController::class, 'changeAddonstatus'])->name('superadmin.addons.status');
    Route::get('/superadmin/websitesetup', [SuperAdminController::class, 'websitesetup'])->name('superadmin.websitesetup');
    Route::get('/websitesetup/{id}/{status}', [SuperAdminController::class, 'websitesetupstatus'])->name('superadmin.websitesetupstatus');
    Route::get('/superadmin/paymentgateway', [SuperAdminController::class, 'paymentgateway'])->name('superadmin.paymentgateway');

    Route::get('/superadmin/modulus/add', [SuperAdminController::class, 'modulusAdd'])->name('superadmin.modulus.add');
    Route::post('/superadmin/modulus/add', [SuperAdminController::class, 'modulusAddStore'])->name('superadmin.modulus.store');
    Route::get('/superadmin/modulus/update-status', [SuperAdminController::class, 'changeModulustatus'])->name('superadmin.modulus.status');

    Route::get('/nitification/view', [SuperAdminController::class, 'viewnotification'])->name('view.notification');
    Route::get('/mobileapps/{id}/{status}', [ChooseplanController::class, 'changestatusmobileapps'])->name('mobileapps');

    Route::post('/deleteallcustomer', [SuperAdminController::class, 'deleteallcustomer'])->name('superadmin.deleteallcustomer');
    Route::post('/deletealldomain', [SuperAdminController::class, 'deletealldomain'])->name('superadmin.deletealldomain');
    Route::post('/changedesign/status', [SuperAdminController::class, 'changedesignssstatus'])->name('superadmin.changedesignssstatus');
    Route::post('/changeiconpack/status', [SuperAdminController::class, 'changeiconpackssstatus'])->name('superadmin.changeiconpackssstatus');
    Route::post('/changetemplate/status', [SuperAdminController::class, 'changetemplatessstatus'])->name('superadmin.changetemplatessstatus');
    Route::post('/changestaffs/status', [SuperAdminController::class, 'changestaffsssstatus'])->name('superadmin.changestaffsssstatus');
    Route::post('/changeroless/status', [SuperAdminController::class, 'changerolessstatus'])->name('superadmin.changerolessstatus');
    Route::post('/changeclients/status', [SuperAdminController::class, 'changeclientssstatus'])->name('superadmin.changeclientssstatus');
    Route::post('/changeclients/setup/status', [SuperAdminController::class, 'changeClientSetupStatus'])->name('superadmin.changeClientSetupStatus');
    Route::post('/changeplanorder/status', [SuperAdminController::class, 'deleteallplanorder'])->name('superadmin.deleteallplanorder');
    Route::post('/changeplanss/status', [PlanController::class, 'changeplansssstatus'])->name('superadmin.changeplansssstatus');
    Route::post('/changeposplanss/status', [PlanController::class, 'changeposplansssstatus'])->name('superadmin.changeposplansssstatus');
    Route::post('/changedigitalplanss/status', [PlanController::class, 'changedigitalplansssstatus'])->name('superadmin.changedigitalplansssstatus');
    Route::post('/changenotification/status', [SuperAdminController::class, 'changenotificationstatus'])->name('superadmin.changenotificationstatus');

    Route::get('/superadmin/invoiceorder', [SuperAdminController::class, 'invoiceorder'])->name('superadmin.invoiceorder');
    Route::get('/superadmin/invoiceorder/accept/{id}', [SuperAdminController::class, 'acceptinvoiceorder'])->name('superadmin.invoiceorder.accept');
    Route::get('/superadmin/invoiceorder/reject/{id}', [SuperAdminController::class, 'rejectinvoiceorder'])->name('superadmin.invoiceorder.reject');
    Route::get('/superadmin/allinvoiceorder', [SuperAdminController::class, 'allinvoiceorder'])->name('superadmin.allinvoiceorder');

    Route::get('/superadmin/customizereq', [SuperAdminController::class, 'customizerequest'])->name('superadmin.customizerequest');
    Route::get('/superadmin/customizerequest/startchat/{token}', [SuperAdminController::class, 'startchats'])->name('superadmin.customizerequest.startchat');
    Route::get('/superadmin/customizerequest/seenchat/{token}', [SuperAdminController::class, 'seentoken'])->name('superadmin.customizerequest.seentoken');
    Route::post('/superadmin/sendmessage/token/{token}', [SuperAdminController::class, 'sendmessagetoken'])->name('superadmin.sendmessage.token');
    Route::get('/superadmin/registration-fee', [SuperAdminController::class, 'registrationFee'])->name('superadmin.registrationFee');
    Route::post('/superadmin/registration-fee', [SuperAdminController::class, 'registrationFeeUpdate'])->name('superadmin.registrationFee.update');

    Route::get('/superadmin/popupimage', [SuperAdminController::class, 'popupimage'])->name('popupimage');
    Route::post('/savepopupimg', [SuperAdminController::class, 'savepopupimg'])->name('savepopupimg');
    Route::get('/superadmin/discounttimmer', [SuperAdminController::class, 'discounttimmer'])->name('discounttimmer');
    Route::post('/savediscounttimmer', [SuperAdminController::class, 'savediscounttimmer'])->name('savediscounttimmer');

    //Digital Marketing
    Route::get('/superadmin/digitalmarketing', [SuperDigitalController::class, 'digitalmarketing'])->name('superadmin.digitalmarketing');
    Route::get('/superadmin/boosting', [SuperDigitalController::class, 'boosting'])->name('superadmin.boosting');
    Route::get('/superadmin/changeboostingstatus/{id}/{status}', [SuperDigitalController::class, 'changeboostingstatus'])->name('superadmin.changeboostingstatus');
    Route::get('/superadmin/deleteboosting/{id}', [SuperDigitalController::class, 'deleteboosting'])->name('superadmin.deleteboosting');
    Route::get('/superadmin/content', [SuperDigitalController::class, 'content'])->name('superadmin.content');
    Route::get('/superadmin/content_correction', [SuperDigitalController::class, 'content_correction'])->name('superadmin.content_correction');
    Route::post('/superadmin/content/save', [SuperDigitalController::class, 'savecontent'])->name('superadmin.savecontent');
    Route::get('/superadmin/content/view/{id}', [SuperDigitalController::class, 'contentview'])->name('content.view');
    Route::get('/superadmin/content/delete/{id}', [SuperDigitalController::class, 'contentdelete'])->name('content.delete');
    Route::get('/contentdetails', [SuperDigitalController::class, 'contentdetails'])->name('contentdetails');
    Route::post('/updatecontent', [SuperDigitalController::class, 'updatecontent'])->name('updatecontent');

    // route created by HK
    Route::get('/superadmin/digitalmarketing/requird/content/view', [SuperDigitalController::class, 'requiredContent'])->name('superadmin.required.content');
    Route::post('/superadmin/digitalmarketing/requird/content/download', [SuperDigitalController::class, 'requiredContentDownload'])->name('superadmin.required.content.download');
    Route::get('/superadmin/digitalmarketing/requird/content/{id}', [SuperDigitalController::class, 'requiredContentView'])->name('superadmin.required.content.view');
    Route::get('/superadmin/digitalmarketing/requird/content/delete/{id}', [SuperDigitalController::class, 'requiredContentDelete'])->name('superadmin.required.content.delete');

    //  This part only for Supuer Admin
    // PSE Products Management

    Route::group(['prefix' => '/superadmin/pse'], function () {
        // PSE Products Manage
        Route::group(['prefix' => '/products'], function () {
            Route::get('/', [PseSuperAdminController::class, 'index'])->name('superadmin.product.pse');
            Route::get('/accepted', [PseSuperAdminController::class, 'pseAccepted'])->name('superadmin.pse.accepted');
            Route::post('/accepted-update', [PseSuperAdminController::class, 'pseAcceptedUpdate'])->name('superadmin.pse.accepted.update');
            Route::get('/rejected', [PseSuperAdminController::class, 'pseRejected'])->name('superadmin.pse.rejected');
            Route::get('/view/{id}', [PseSuperAdminController::class, 'pseView'])->name('superadmin.pse.select.view');
            Route::get('/search', [PseSuperAdminController::class, 'superAdminListSearch'])->name('superadmin.pse.search');
            Route::get('accepted-search', [PseSuperAdminController::class, 'superAdminAcceptedListSearch'])->name('superadmin.pse.accepted.search');
            Route::get('/delete', [PseSuperAdminController::class, 'pseDeleteProduct'])->name('superadmin.pse.delete');
            Route::get('/position', [PseSuperAdminController::class, 'pseProductPosition'])->name('superadmin.pse.position');
            Route::get('/status', [PseSuperAdminController::class, 'pseStatus'])->name('superadmin.pse.status');
            Route::get('/visitor', [PseSuperAdminController::class, 'pseVisitor'])->name('superadmin.pse.visitor');
            Route::get('/visitor/{id}', [PseSuperAdminController::class, 'pseStoreVisitor'])->name('superadmin.pse.visitor.details');
            Route::get('/static-visitor', [PseSuperAdminController::class, 'pseStaticVisitor'])->name('superadmin.pse.static.visitor');
            Route::get('/visitor-search', [PseSuperAdminController::class, 'superVisitorListSearch'])->name('superadmin.pse.visitor.search');
        });

        // PSE Ad Manage
        Route::group(['prefix' => '/ad'], function () {
            Route::get('/', [PseAdSuperAdminController::class, 'AdPse'])->name('superadmin.pse.ad');
            Route::get('/create', [PseAdSuperAdminController::class, 'AdCreatePse'])->name('superadmin.pse.create');
            Route::post('/create', [PseAdSuperAdminController::class, 'AdPseStore'])->name('superadmin.pse.store');
            Route::get('/edit/{id}', [PseAdSuperAdminController::class, 'AdEditPse'])->name('superadmin.pse.edit');
            Route::post('/edit/{id}', [PseAdSuperAdminController::class, 'AdUpdatePse'])->name('superadmin.pse.update');
            Route::get('/status', [PseAdSuperAdminController::class, 'AdPseStatus'])->name('superadmin.pse.ad.status');
            Route::get(
                '/position',
                [PseAdSuperAdminController::class, 'AdPsePosition']
            )->name('superadmin.pse.ad.position');
            Route::get(
                '/delete/{id}',
                [PseAdSuperAdminController::class, 'AdDeleteFromPse']
            )->name('superadmin.pse.ad.delete');
        });

        // PSE Category Manage
        Route::group(['prefix' => '/category'], function () {
            Route::get('/', [PseCategoryController::class, 'index'])->name('pse.category');
            Route::post('/', [PseCategoryController::class, 'storeCategory'])->name('pse.category.store');
            Route::get('/edit/{id}', [PseCategoryController::class, 'categoryEdit'])->name('pse.category.edit');
            Route::post('/edit/{id}', [PseCategoryController::class, 'categoryUpdate'])->name('pse.category.update');
            Route::get('/delete/{id}', [PseCategoryController::class, 'categoryDelete'])->name('pse.category.delete');
            Route::get('/position', [PseCategoryController::class, 'pseCategoryPosition'])->name('pse.category.position');
            Route::get('/status', [PseCategoryController::class, 'pseCategoryStatus'])->name('pse.category.status');
        });
    });
    // END PSE

    // categories
    Route::get('/superadmin/category', [SuperAdminCategoryController::class, 'index'])->name('superadmin.store.category');
    Route::post('/superadmin/category', [SuperAdminCategoryController::class, 'store'])->name('superadmin.store.category.store');

    Route::get('/superadmin/category/add/{id}', [SuperAdminCategoryController::class, 'catAdd'])->name('superadmin.store.category.catAdd');
    Route::post('/superadmin/category/add', [SuperAdminCategoryController::class, 'catAddStore'])->name('superadmin.store.category.catAddStore');
    Route::get('/superadmin/category/changecatstatus', [SuperAdminCategoryController::class, 'changecatstatus'])->name('superadmin.store.category.changecatstatus');
    Route::get('/superadmin/category/{id}/edit', [SuperAdminCategoryController::class, 'edit'])->name('superadmin.store.category.edit');
    Route::PUT('/superadmin/category/{id}/edit', [SuperAdminCategoryController::class, 'update'])->name('superadmin.store.category.edit.update');
    Route::get('/superadmin/category/{id}/delete', [SuperAdminCategoryController::class, 'deletecat'])->name('superadmin.store.category.deletecat');
    Route::get('/superadmin/category/updateposition', [SuperAdminCategoryController::class, 'updateposition'])->name('superadmin.store.category.updateposition');
    Route::get('/superadmin/store-management', [StoreManageController::class, 'index'])->name('superadmin.store.manage');
    Route::post('/superadmin/store/delete', [StoreManageController::class, 'storeDelete'])->name('superadmin.store.delete');
    Route::post('/superadmin/store/delete-multi', [StoreManageController::class, 'storeDeleteMulti'])->name('superadmin.store.delete.multi');
    Route::post('/superadmin/store/name/update', [StoreManageController::class, 'updateStoreName'])->name('superadmin.update.store.name');


    // Api for store delete confirmation auth
    Route::get('/superadmin/store-delete-auth-check/', [StoreManageController::class, 'storeDeleteAuthCheck'])->name('superadmin.store.store.delete.auth.check');

    // route created end
    // File Control
    Route::get('/filecontrol', [FileControlController::class, 'filecontrol'])->name('filecontrol');
    Route::post('/fileuploads', [FileControlController::class, 'fileuploads'])->name('fileuploads');
    Route::post('/newupload', [FileControlController::class, 'fileuploads'])->name('fileuploads.newupload');
    Route::get('/deletefile/{id}', [FileControlController::class, 'deletefile'])->name('deletefile');
    Route::get('/deletedataa/{domain}', [FileControlController::class, 'deletedataa'])->name('deletedataa');
    Route::post('/copyfilee', [FileControlController::class, 'copyfile'])->name('copyfile');
    Route::post('/copyfilemultiple', [FileControlController::class, 'copyfilemultiple'])->name('copyfilemultiple');
    Route::post('/deletedomainsdata', [FileControlController::class, 'deletedomainsdata'])->name('deletedomainsdata');

    Route::get('/testMY', [FileControlController::class, 'testMY'])->name('testMY');

    // Store status change
    Route::get('/change/store/status', [StoreManageController::class, 'storeStatus'])->name('superadmin.store.status.change');

    // Order status
    Route::get('/order-status/{id?}', [OrderStatusController::class, 'index'])->name('superadmin.order.status.index');
    Route::post('/order-status', [OrderStatusController::class, 'store'])->name('superadmin.order.status.store');
    Route::post('/change-order-status', [OrderStatusController::class, 'changeBlogTypeStatus'])->name('superadmin.order.status.change');
    Route::get('/single-change-order-status', [OrderStatusController::class, 'singleBlogTypestatusChange'])->name('superadmin.single.order.status.change');
    Route::post('/delete-order-status', [OrderStatusController::class, 'deleteBlogType'])->name('superadmin.delete.order.status');
    Route::get('/client-sales-report', [ReportController::class, 'clientSalesReport'])->name('superadmin.report');
    Route::get('/sms-log-report', [ReportController::class, 'storeSmsList'])->name('superadmin.store.sms.list');
    Route::get('/store/sms-log-report/{id?}', [ReportController::class, 'smsLogReport'])->name('superadmin.sms.log.report');


    // Chatbot start
    Route::group(['prefix' => '/chat-bot'], function () {
        Route::get('/', [ChatBotController::class, 'index'])->name('chatBot.index');
        Route::get('/question-create', [ChatBotController::class, 'create'])->name('chatBot.questions.create');
        Route::post('/question-store', [ChatBotController::class, 'store'])->name('chatBot.questions.store');
        Route::get('/question-edit/{id}', [ChatBotController::class, 'edit'])->name('chatBot.questions.edit');
        Route::post('/question-update', [ChatBotController::class, 'update'])->name('chatBot.questions.update');
        Route::get('/group-delete/{id}', [ChatBotController::class, 'delete'])->name('chatBot.group.delete');
        Route::get('/answer-delete/{id}', [ChatBotController::class, 'deleteAnswer'])->name('chatBot.answer.delete');
        Route::get('/question-status', [ChatBotController::class, 'changeStatus'])->name('chatBot.questions.status');
        Route::post('/question-action', [ChatBotController::class, 'actionChange'])->name('chatBot.questions.action');
        Route::get('/question-delete/{id}', [ChatBotController::class, 'deleteQuestion'])->name('chatBot.question.delete');
        Route::post('/search-answer', [ChatbotController::class, 'searchAnswer'])->name('chatBot.searchAnswer');

        // Support analytics
        Route::get('/support-analytics', [ChatBotController::class, 'supportAnalytics'])->name('chatBot.support.analytics');

        // Unanswered question route
        Route::get('/unanswered/questions', [ChatBotController::class, 'unansweredQuestionsList'])->name('chatBot.unansweredQuestions.list');
        Route::get('/unanswered/create/{id}', [ChatBotController::class, 'unansweredQuestionsCreate'])->name('chatBot.unansweredQuestions.create');
        Route::post('/unanswered/store', [ChatBotController::class, 'unansweredQuestionsStore'])->name('chatBot.unansweredQuestions.store');
        Route::post('/unanswered/question-action', [ChatBotController::class, 'unansweredQuestionsActionChange'])->name('chatBot.unansweredQuestions.action');
        Route::get('/unanswered/delete/{id}', [ChatBotController::class, 'unansweredQuestionsDelete'])->name('chatBot.unansweredQuestions.delete');

        // Bot conversation list
        Route::get('/bot-conversation/list', [ChatBotController::class, 'botConversationList'])->name('chatBot.botConversation.list');

        // Agent conversation list
        Route::get('/agent-conversation/list', [ChatBotController::class, 'agentConversationList'])->name('chatBot.agentConversation.list');

        Route::post('/assign-conversation-agent', [ChatBotController::class, 'conversationAssignAgent'])->name('chatBot.conversationAssignAgent');
    });

    // Chatbot end
    Route::get('/cpanel/zone/record/{id?}', [SuperAdminController::class, 'cpanelZoneRecord'])->name('cpanel.zone.record');
    Route::get('/delete/cpanel/zone/record/{id}', [SuperAdminController::class, 'deleteCpanelZoneRecord'])->name('delete.cpanel.zone.record');
    Route::post('/store/cpanel/zone/record', [SuperAdminController::class, 'storeOrUpdateZoneRecord'])->name('store.cpanel.zone.record');
    Route::post('/delete-selected/zone/record', [SuperAdminController::class, 'deleteSelectedRecord'])->name('cpanel.zone.record.delete');

    Route::get('/amar-pay/kyc/list/{status?}', [MarchantPaymentGetwayKYCController::class, 'amarpayKYCList'])->name('superadmin.amaypay.kyc');
    Route::get('/amar-pay/kyc/view/{id}', [MarchantPaymentGetwayKYCController::class, 'amarpayKYCView'])->name('superadmin.amaypay.kyc.view');
    Route::get('/accept/kyc/view/{id}', [MarchantPaymentGetwayKYCController::class, 'amarpayAcceptKYCView'])->name('superadmin.accept.kyc.view');
    Route::get('/amar-pay/kyc/status/change/{id}/{status}', [MarchantPaymentGetwayKYCController::class, 'amarpayKYCStatusChnage'])->name('superadmin.amaypay.kyc.status.change');
    Route::get('/merchant-payment-list', [MarchantPaymentGetwayController::class, 'merchantPaymentList'])->name('superadmin.amaypay.payment.list');
    Route::get('/merchant-order-list/{store}', [MarchantPaymentGetwayController::class, 'merchantOrderList'])->name('superadmin.merchant.order.list');
    Route::get('/amarpay-client-list', [MarchantPaymentGetwayKYCController::class, 'amarPayClientList'])->name('superadmin.amaypay.client.list');
    Route::post('/set-merchant-withdraw-amount', [MarchantPaymentGetwayController::class, 'setWithdrawAmount'])->name('superadmin.merchant.setWithdraw');
    Route::get('/merchant-active-status', [MarchantPaymentGetwayController::class, 'merchantActiveStatus'])->name('superadmin.merchant.active.status');

    Route::get('/amarpay-payment-withdraw-request-list/{status?}', [MarchantPaymentGetwayController::class, 'amarpayPaymentWithdrawList'])->name('superadmin.amaypay.withdraw.request.list');
    Route::get('/amarpay/withdraw-status/change/{id}/{status}', [MarchantPaymentGetwayController::class, 'amarpayPaymentWithdrawStatusChange'])->name('superadmin.amaypay.withdraw.status.change');


    /*********** Sell Commission start **********/
    // All sell user
    Route::get('/sell-commission', [SellCommissionController::class, 'index'])->name('superadmin.sell.commission');

    // All sell user
    Route::get('/sell-commission/overflow', [SellCommissionController::class, 'overFlowList'])->name('superadmin.sell.commission.overflow.list');

    // Change sell commission
    Route::post('/sell-commission/commission/update', [SellCommissionController::class, 'commissionUpdate'])->name('superadmin.update.sell.commission');

    // Change sell order pull
    Route::post('/sell-commission/order-pull/update', [SellCommissionController::class, 'orderPullUpdate'])->name('superadmin.update.sell.order.pull');

    // Change sell overflow commission
    Route::post('/sell-commission/overflow-commission/update', [SellCommissionController::class, 'overflowCommissionUpdate'])->name('superadmin.update.sell.order.overflow.commission');

    // Change sell overflow commission
    Route::get('/sell-commission/store/order/{id}', [SellCommissionController::class, 'storeOrderDetails'])->name('superadmin.sell.order.details');
    /*********** Sell Commission end **********/


});


Route::group(['middleware' => ['auth', 'isAdminOrSuperAdmin', 'isModulusAccess:116']], function () {
    // Start Blogs
    Route::group(['prefix' => '/blogs'], function () {
        Route::get('/', [AdminBlogController::class, 'index'])->name('superadmin.blog.index');
        Route::get('/create', [AdminBlogController::class, 'create'])->name('superadmin.blog.create');
        Route::get('/edit/{id}', [AdminBlogController::class, 'edit'])->name('superadmin.blog.edit');
        Route::post('/edit/{id}', [AdminBlogController::class, 'update'])->name('superadmin.blog.update');
        Route::get('/position', [AdminBlogController::class, 'pseBlogPosition'])->name('superadmin.blog.position');
        Route::get('/status', [AdminBlogController::class, 'blogStatus'])->name('superadmin.blog.status');
        Route::get('/delete/{id}', [AdminBlogController::class, 'blogDelete'])->name('superadmin.blog.delete');
        Route::post('/store', [AdminBlogController::class, 'store'])->name('superadmin.blog.store');
        Route::post('/ck', [AdminBlogController::class, 'ckEditor'])->name('superadmin.blog.ck');
        Route::post('/update-cover-image', [AdminBlogController::class, 'updateCoverImage'])->name('superadmin.blog.update.cover.image');

        // blog type
        Route::get('/type/{id?}', [AdminBlogTypeController::class, 'index'])->name('superadmin.blog.type.index');
        Route::post('/type', [AdminBlogTypeController::class, 'store'])->name('superadmin.blog.type.store');
        Route::post('/change-blog-type-status', [AdminBlogTypeController::class, 'changeBlogTypeStatus'])->name('blog.type.status.change');
        Route::get('/single-change-blog-type-status', [AdminBlogTypeController::class, 'singleBlogTypestatusChange'])->name('single.blog.type.status.change');
        Route::post('/delete-blog-type', [AdminBlogTypeController::class, 'deleteBlogType'])->name('delete.blog.type');

        Route::post('/action-change', [AdminBlogController::class, 'blogActionChange'])->name('blog.action.change');
    });
});


Route::get('/admin/notification', [AdminNotificationController::class, 'notification'])->name('admin.notification');
Route::get('/admin/notification/create', [AdminNotificationController::class, 'createnotification'])->name('admin.notification.create');
Route::post('/admin/notification/save', [AdminNotificationController::class, 'savenotification'])->name('admin.notification.save');
Route::get('/admin/notification/edit/{id}', [AdminNotificationController::class, 'editnotification'])->name('admin.notification.edit');
Route::post('/admin/notification/update/{id}', [AdminNotificationController::class, 'updatenotification'])->name('admin.notification.update');
Route::get('/admin/notification/delete/{id}', [AdminNotificationController::class, 'deletenotification'])->name('admin.notification.delete');
Route::post('/admin/changenotification/status', [AdminNotificationController::class, 'changenotificationstatus'])->name('admin.changenotificationstatus');

Route::post('/saveaudio', [AdminController::class, 'saveaudio']);

Route::get('/pro', [ProductController::class, 'pro']);
Route::get('/staff/login', [StaffLoginController::class, 'stafflogin'])->name('staff.login');
Route::get('/staff/dashboard', [StaffLoginController::class, 'dashboard'])->name('staff.dashboard');
Route::post('/staff/login/submit', [StaffLoginController::class, 'staffloginsubmit'])->name('staff.submitlogin');
Route::get('/superstaff/commission', [StaffLoginController::class, 'superstaffCommission'])->name('superstaff.commission');
Route::get('/superstaff/commission/payment/history', [StaffLoginController::class, 'superstaffCommissionPaymentHistory'])->name('superstaff.commission.payment.history');
Route::get('/superstaff/access/account/{id}', [StaffLoginController::class, 'accessAdminAccount'])->name('superstaff.access.admin');
Route::get('/superstaff/access/admin-store/{id}', [StaffLoginController::class, 'accessAdminStore'])->name('superstaff.access.admin.store');

Route::get('/getnoti', [SuperAdminController::class, 'getnotification']);
Route::get('/branchdel', [BranchController::class, 'branchdel'])->name('branchdel');
Route::get('/restoredeletebranch/{id}', [BranchController::class, 'restoredeletebranch'])->name('restoredeletebranch');
Route::get('/superadmindeletebranch/{id}', [BranchController::class, 'superadmindeletebranch'])->name('superadmindeletebranch');

Route::get('/otp-verify', [OtpverifyController::class, 'verify'])->name('checkotp');
Route::post('/match-code', [OtpverifyController::class, 'checkverify'])->name('match.code');
Route::get('/get-code', [OtpverifyController::class, 'getcode'])->name('getcode');
Route::get('/choose-plan', [ChooseplanController::class, 'chooseplan'])->name('planchoose');
Route::get('/choose_plans', [ChooseplanController::class, 'chooseplans'])->name('planchooses');
Route::get('/checkurlstore', [ChooseplanController::class, 'checkurlname'])->name('checkurlname');
Route::get('/check-store-name', [ChooseplanController::class, 'checkStoreName'])->name('check.store.name');
Route::get('/active-plan/{id}/{month}', [ChooseplanController::class, 'activeplans'])->name('planactive');
Route::get('/admin/payment/payments', [ChooseplanController::class, 'payment_payments'])->name('payment.payments');
Route::get('/admin/payment/payments/invoice/{id}', [ChooseplanController::class, 'paymentInvoiceById'])->name('payment.payments.invoice');
Route::post('/admin/payment/payments/{id}/update-due', [ChooseplanController::class, 'updateManualDuePayment'])->name('payment.payments.update-due');
Route::get('/admin/payment/addons', [ChooseplanController::class, 'payment_addons'])->name('payment.addons');
Route::get('/admin/payment/packages', [ChooseplanController::class, 'payment_packages'])->name('payment.packages');
Route::get('/admin/payment/packages/{id}', [ChooseplanController::class, 'payment_packages_view'])->name('payment.packages.view');
Route::post('/admin/get-coupon', [ChooseplanController::class, 'getCoupon']);

//Route::post('/admin/buy-addons-bkash', [ChooseplanController::class, 'buyAddonsWithBkash']);
Route::post('/admin/buy-addons-manual', [ChooseplanController::class, 'buyAddonsWithManual']);
//Route::post('admin/buy-addons-paypal', [ChooseplanController::class, 'buyAddonsWithPayPal']);
Route::post('admin/save-addons-order', [ChooseplanController::class, 'buyAddons']);


// Route::get('/admin/payment',[ChooseplanController::class,'payments'])->name('paymentss');
Route::post('/placeplan', [ChooseplanController::class, 'placeplan'])->name('placeplan');
Route::get('/changeplanss', [ChooseplanController::class, 'changeplan'])->name('changeplan');

Route::post('/sendotpfp', [OtpverifyController::class, 'sendotpfp'])->name('sendotpfp');
Route::get('/fpotp', [OtpverifyController::class, 'fpotp'])->name('fpotp');
Route::post('/submitfpotp', [OtpverifyController::class, 'verifyfpotp'])->name('submitfpotp');
Route::get('/new-password', [OtpverifyController::class, 'GEtverifyfpotp'])->name('submitfpotp.agin');
Route::get('/fpcodeagain', [OtpverifyController::class, 'sendotpfp']);
Route::post('/changepass', [OtpverifyController::class, 'changepass'])->name('changepass');

Route::get('/plans', [PlanController::class, 'backplan'])->name('plans');
Route::get('/posplans', [PlanController::class, 'posplan'])->name('posplans');
Route::get('/digitalplans', [PlanController::class, 'digitalplan'])->name('digitalplans');
Route::get('/plans/create', [PlanController::class, 'plancreate'])->name('plans.create');
Route::get('/posplans/create', [PlanController::class, 'posplancreate'])->name('posplans.create');
Route::get('/digitalplans/create', [PlanController::class, 'digitalplancreate'])->name('digitalplans.create');
Route::post('/plan/save', [PlanController::class, 'saveplan'])->name('saveplan');
Route::post('/posplan/save', [PlanController::class, 'saveposplan'])->name('saveposplan');
Route::post('/digitalplan/save', [PlanController::class, 'savedigitalplan'])->name('savedigitalplan');
Route::get('/plan/edit/{id}', [PlanController::class, 'editplan'])->name('editplan');
Route::get('/posplan/edit/{id}', [PlanController::class, 'editposplan'])->name('editposplan');
Route::get('/digitalplan/edit/{id}', [PlanController::class, 'editdigitalplan'])->name('editdigitalplan');
Route::post('/plan/update/{id}', [PlanController::class, 'updateplan'])->name('updateplan');
Route::post('/posplan/update/{id}', [PlanController::class, 'updateposplan'])->name('updateposplan');
Route::post('/digitalplan/update/{id}', [PlanController::class, 'updatedigitalplan'])->name('updatedigitalplan');
Route::get('/plan/delete/{id}', [PlanController::class, 'deleteplan'])->name('deleteplan');
Route::get('/posplan/delete/{id}', [PlanController::class, 'deleteposplan'])->name('deleteposplan');
Route::get('/digitalplan/delete/{id}', [PlanController::class, 'deletedigitalplan'])->name('deletedigitalplan');

// Payment Processing charge
Route::get('/plan/payment/list/{type}/{id}', [PaymentProcesserController::class, 'paymentList'])->name('plan.payment.list');
Route::get('/plan/payment/create/{type}/{id}', [PaymentProcesserController::class, 'paymentCreate'])->name('plan.payment.create');
Route::post('/plan/payment/store', [PaymentProcesserController::class, 'paymentStore'])->name('plan.payment.store');
Route::get('/plan/payment/edit/{id}', [PaymentProcesserController::class, 'paymentEdit'])->name('plan.payment.edit');
Route::post('/plan/payment/update', [PaymentProcesserController::class, 'paymentUpdate'])->name('plan.payment.update');
Route::get('/plan/payment/delete/{id}', [PaymentProcesserController::class, 'paymentDelete'])->name('plan.payment.delete');


// SSLCOMMERZ Start
Route::get('/example1', [SslCommerzPaymentController::class, 'exampleEasyCheckout']);
Route::get('/example2', [SslCommerzPaymentController::class, 'exampleHostedCheckout']);

Route::post('/pay', [SslCommerzPaymentController::class, 'index']);
Route::post('/pay-via-ajax', [SslCommerzPaymentController::class, 'payViaAjax']);

//Route::post('/success', [SslCommerzPaymentController::class, 'success']);
//Route::post('/fail', [SslCommerzPaymentController::class, 'fail']);
//Route::post('/cancel', [SslCommerzPaymentController::class, 'cancel']);
//
//Route::post('/ipn', [SslCommerzPaymentController::class, 'ipn']);
//SSLCOMMERZ END

// Client Analytics hk
Route::get('/admin/analytics', [ClientController::class, 'index'])->name('admin.ebitans.analytics');
Route::get('/admin/analytics/all-url', [ClientController::class, 'allUrl'])->name('admin.ebitans.analytics.all.url');
Route::get('/admin/analytics/all-traffic', [ClientController::class, 'allTraffic'])->name('admin.ebitans.analytics.all.traffic');
Route::get('/admin/analytics/store', [ClientController::class, 'allStore'])->name('admin.ebitans.analytics.all.store');
Route::get('/admin/analytics/store-wise/{id}', [ClientController::class, 'storeWise'])->name('admin.ebitans.analytics.store.wise');

//Product Khujo visitors
Route::get('/admin/visitors', [ProductController::class, 'allVisitor'])->name('all.visitor');
Route::get('/admin/product-khujo', [ProductController::class, 'productKhujo'])->name('product.khujo');
Route::get('/admin/weekly-report', [ProductController::class, 'weeklyReport'])->name('weekly.report');
Route::get('/admin/monthly-report', [ProductController::class, 'weeklyReport'])->name('monthly.report');
//End product Khujo visitors

// End Client Analytics hk

// Ebitans Analytics hk
Route::get('/superadmin/ebi-analytics', [AnalyticsController::class, 'index'])->name('super.admin.ebitans.analytics');
Route::get('/superadmin/ebi-analytics/all-url', [AnalyticsController::class, 'allUrl'])->name('super.admin.ebitans.analytics.all.url');
Route::get('/superadmin/ebi-analytics/all-traffic', [AnalyticsController::class, 'allTraffic'])->name('super.admin.ebitans.analytics.all.traffic');
Route::get('/superadmin/ebi-analytics/store', [AnalyticsController::class, 'allStore'])->name('super.admin.ebitans.analytics.all.store');
Route::get('/superadmin/ebi-analytics/store-wise/{id}', [AnalyticsController::class, 'storeWise'])->name('super.admin.ebitans.analytics.store.wise');
Route::get('/superadmin/ebi-analytics/website-visitor', [AnalyticsController::class, 'websiteVisitor'])->name('super.admin.ebitans.analytics.website.visitor');
// End Ebitans Analytics hk

//Ebitans Admin Analytics
Route::get('/superadmin/ebi-admin-analytics', [AdminUserAnalyticsController::class, 'index'])->name('super.admin.ebitans.backend.analytics');
//Ebitans Admin Analytics

Route::get('/admin/testing-dashboard', [AdminController::class, 'testingDashboard'])->name('admin.testingDashboard');

// Route::post('/token', [BkashController::class, 'token'])->name('token');
// Route::get('/createpayment', [BkashController::class, 'createpayment'])->name('createpayment');
// Route::get('/executepayment', [BkashController::class, 'executepayment'])->name('executepayment');

// Checkout (URL) User Part
Route::get('/bkash/pay', [BkashController::class, 'payment'])->name('url-pay');
Route::get('/bkash/create', [BkashController::class, 'createPayment'])->name('url-create');
Route::get('/bkash/callback', [BkashController::class, 'callback'])->name('url-callback');

// Checkout (URL) Admin Part
Route::get('/admin/bkash/refund', [BkashController::class, 'getRefund'])->name('url-get-refund');
Route::post('/admin/bkash/refund', [BkashController::class, 'refundPayment'])->name('url-post-refund');

// Checkout (URL) User Part
Route::get('/admin/bkash/pay', [AdminBkashController::class, 'payment'])->name('admin.url-pay');
Route::get('/admin/bkash/create', [AdminBkashController::class, 'createPayment'])->name('admin.url-create');
Route::get('/admin/bkash/callback', [AdminBkashController::class, 'callback'])->name('admin.url-callback');

// Checkout (URL) Admin Part
Route::get('/admin/bkash/refund', [AdminBkashController::class, 'getRefund'])->name('admin.url-get-refund');
Route::post('/admin/bkash/refund', [AdminBkashController::class, 'refundPayment'])->name('admin.url-post-refund');

// SSL Checkout (URL) User Part
Route::get('/ssl/create', [SSLController::class, 'createPayment'])->name('ssl.create-payment');
Route::post('/success', [SSLController::class, 'success'])->name('ssl.success');
Route::post('/fail', [SSLController::class, 'fail'])->name('ssl.fail');
Route::post('/cancel', [SSLController::class, 'cancel'])->name('ssl.cancel');
Route::post('/ipn', [SSLController::class, 'ipn'])->name('ssl.ipn');

//Super admin settings
Route::middleware(['auth', 'superadmin'])->prefix('/superadmin/settings')->name('super_admin.settings.')->group(function () {
    Route::get('/', [SuperAdminController::class, 'superAdminSetting'])->name('index');
    Route::post('/save/super-admin-setting', [SuperAdminController::class, 'saveSuperAdminSetting'])->name('saveSuperAdminSetting');

    /*currency*/
    Route::controller(CurrencyController::class)->prefix('/currency')->group(function () {
        Route::get('/', 'index')->name('currency_list');
        Route::post('/', 'store')->name('currency_store');
        Route::put('/{id}', 'update')->name('currency_update');
        Route::post('/change-status', 'status_change')->name('currency_status_change');
        Route::post('/change-rate-status', 'status_rate_change')->name('currency_status_rate_change');
    });

    Route::get('/store-static-data', [SuperAdminController::class, 'getStoreStaticData'])->name('store.static.data');
    Route::post('/save/store-static-data', [SuperAdminController::class, 'saveStoreStaticData'])->name('save.store.static.data');

    Route::get('/superstaff-allowed-ips', [SuperAdminController::class, 'superstaffAllowedIps'])->name('superstaff_allowed_ips.index');
    Route::post('/superstaff-allowed-ips/restriction', [SuperAdminController::class, 'updateSuperstaffIpRestriction'])->name('superstaff_allowed_ips.restriction');
    Route::post('/superstaff-allowed-ips', [SuperAdminController::class, 'storeSuperstaffAllowedIp'])->name('superstaff_allowed_ips.store');
    Route::put('/superstaff-allowed-ips/{id}', [SuperAdminController::class, 'updateSuperstaffAllowedIp'])->name('superstaff_allowed_ips.update');
    Route::delete('/superstaff-allowed-ips/{id}', [SuperAdminController::class, 'deleteSuperstaffAllowedIp'])->name('superstaff_allowed_ips.destroy');

});

// Common things
Route::controller(CommonController::class)->prefix('/common')->group(function () {
    Route::get('/flash_exchange_rate', 'flash_exchange_rate')->name('flash_exchange_rate');
});


Route::group(['middleware' => ['auth', 'isAdminOrSuperAdmin']], function () {
    // Get conversation list for admin or super admin (if admin then get admin store user or if super admin then get super admin client)
    Route::get('/get-chat/user/list', [\App\Http\Controllers\ChatSystem\ChatController::class, 'getUser'])->name('chat.getUser');

    // Dispaly chat ui for admin or super admin
    Route::get('/u/chat', [\App\Http\Controllers\ChatSystem\ChatController::class, 'index'])->name('chat.index');

    // Create conversation
    Route::post('/auth/create/chat-conversation', [App\Http\Controllers\Api\v1\chat\ChatController::class, 'createConversation'])->name('auth.create.conversation');

    // Get single conversation by visitor ID
    Route::get('/auth/get/chat-conversations/{id}', [App\Http\Controllers\Api\v1\chat\ChatController::class, 'getConversationByVisitorId'])->name('auth.get.conversation.byID');

    // Create conversation
    Route::get('/auth/get-chat-conversations', [App\Http\Controllers\Api\v1\chat\ChatController::class, 'getConversation'])->name('auth.get.conversation');

    // Authenticated message operations for support agents
    Route::get('/auth/get-conversations/message/{id?}', [App\Http\Controllers\Api\v1\chat\ChatController::class, 'getConversationMessage'])->name('auth.get.conversation.message');
    Route::put('/auth/chat-massage/markAsRead', [App\Http\Controllers\Api\v1\chat\ChatController::class, 'massageMarkAsRead'])->name('auth.message.markAsRead');
    Route::post('/auth/chat-message/send', [App\Http\Controllers\Api\v1\chat\ChatController::class, 'massageSend'])->name('auth.message.send');
    Route::get('/auth/chat-realtime/events', [App\Http\Controllers\Api\v1\chat\RealtimeController::class, 'events'])->name('auth.realtime.events');
    Route::get('/auth/chat-realtime/stream', [App\Http\Controllers\Api\v1\chat\RealtimeController::class, 'stream'])->name('auth.realtime.stream');
});


Route::fallback(function () {
    $path = request()->path();

    if (str_starts_with($path, 'api/')) {
        return response()->json(['message' => 'Not found.'], 404);
    }

    if (
        preg_match('/\.(jpg|jpeg|png|gif|webp|css|js|svg|ico|woff|ttf|map)$/i', $path) ||
        str_starts_with($path, 'assets/')
    ) {
        abort(404);
    }

    return redirect('/');
});


require __DIR__ . '/auth.php';
