<?php

use App\Http\Controllers\Api\v1\BookingController;
use App\Http\Controllers\Api\v1\EbitansAnalytics\EbtAnalyticsController;
use App\Http\Controllers\SuperAdmin\Affiliate\AffiliateController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ResponseController;
use App\Http\Controllers\Api\v1\LoginController;
use App\Http\Controllers\Api\v1\SubdomainController;
use App\Http\Controllers\Api\v1\OrderController;
use App\Http\Controllers\Api\v1\UserController;
use App\Http\Controllers\Api\v1\PaymentPageController;
use App\Http\Controllers\Api\v1\ImageController;
use App\Http\Controllers\Api\v1\AdminBlogController;
use App\Http\Controllers\Api\v1\AnnouncementController;
use App\Http\Controllers\Api\v1\Marketplace\MarketplaceController;
use App\Http\Controllers\PaymentGateway\BkashController;
use App\Http\Controllers\Api\v1\NewsLetterController;
use App\Http\Controllers\Api\v1\PosController;
use App\Http\Controllers\Api\v1\ThemeController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\PaymentGateway\AdminBkashController;
use App\Http\Controllers\QuickLoginController;
use App\Http\Controllers\Api\v1\PseAdsController;
use App\Http\Controllers\ProductAffiliateController;
use App\Http\Controllers\Api\v1\DistrictController;
use App\Http\Controllers\DesignController;
use App\Http\Controllers\Api\v2\MarketingController;
use App\Http\Controllers\WhatsAppAutomation\AuthController;
use App\Http\Controllers\WhatsAppAutomation\DashboardController;
use App\Http\Controllers\WhatsAppAutomation\LeadController;
use App\Http\Controllers\WhatsAppAutomation\HandoffController;
use App\Http\Controllers\WhatsAppAutomation\RealtimeController;
use App\Http\Controllers\WhatsAppAutomation\ReviewController;
use App\Http\Controllers\WhatsAppAutomation\LearningController;
use App\Http\Controllers\WhatsAppAutomation\CampaignController;
use App\Http\Controllers\WhatsAppAutomation\KnowledgeController;
use App\Http\Controllers\WhatsAppAutomation\OutboundController;
use App\Http\Controllers\WhatsAppAutomation\AnalyticsController;
use App\Http\Controllers\WhatsAppAutomation\TrainingController;
use App\Http\Controllers\WhatsAppAutomation\CohortController;
use App\Http\Controllers\WhatsAppAutomation\GatewayWebhookController;
use App\Http\Controllers\WhatsAppAutomation\GatewaySessionController;
use App\Http\Controllers\WhatsAppAutomation\LiveClientShowcaseController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Stable public URL for bots/docs; do not derive URI from config alone (route/config cache can omit it).
Route::get('whatsapp/live-client-showcase', [LiveClientShowcaseController::class, 'botIndex']);
$liveClientShowcaseAlias = config('whatsapp_automation.live_client_showcase_path');
if ($liveClientShowcaseAlias !== '' && $liveClientShowcaseAlias !== 'whatsapp/live-client-showcase') {
    Route::get($liveClientShowcaseAlias, [LiveClientShowcaseController::class, 'botIndex']);
}

Route::prefix('whatsapp')->group(function () {
    Route::post('/auth/verify', [AuthController::class, 'verify']);
    Route::get('/webhooks/gateway', [GatewayWebhookController::class, 'health']);
    Route::post('/webhooks/gateway', [GatewayWebhookController::class, 'receive']);

    Route::middleware(['whatsapp.react'])->group(function () {
        Route::get('/auth/me', [AuthController::class, 'me']);
        Route::post('/auth/logout', [AuthController::class, 'logout']);
        Route::post('/gateway/sessions/create', [GatewaySessionController::class, 'create']);
        Route::get('/gateway/sessions/{tenantId}/status', [GatewaySessionController::class, 'status']);
        Route::get('/gateway/sessions/{tenantId}/qr', [GatewaySessionController::class, 'qr']);
        Route::post('/gateway/sessions/{tenantId}/logout', [GatewaySessionController::class, 'logout']);
        Route::get('/dashboard', [DashboardController::class, 'index']);
        Route::get('/realtime/stream', [RealtimeController::class, 'stream']);
        Route::get('/realtime/events', [RealtimeController::class, 'events']);

        Route::get('/leads', [LeadController::class, 'index']);
        Route::get('/leads/{sessionId}', [LeadController::class, 'show']);
        Route::post('/leads/{sessionId}/status', [LeadController::class, 'updateStatus']);
        Route::post('/leads/{sessionId}/auto-reply', [LeadController::class, 'updateAutoReply']);
        Route::post('/leads/{sessionId}/promised-payment', [LeadController::class, 'updatePromisedPayment']);

        // Tag routes
        Route::get('/leads/{sessionId}/tags', [LeadController::class, 'tags']);
        Route::post('/leads/{sessionId}/tags', [LeadController::class, 'assignTag']);
        Route::delete('/leads/{sessionId}/tags/{tagName}', [LeadController::class, 'removeTag']);
        Route::post('/leads/{sessionId}/tags/refresh', [LeadController::class, 'refreshTags']);
        Route::get('/leads/{sessionId}/history', [LeadController::class, 'history']);
        Route::get('/leads/{sessionId}/comments', [LeadController::class, 'comments']);
        Route::post('/leads/{sessionId}/comments', [LeadController::class, 'addComment']);
        Route::post('/messages/{messageId}/feedback', [LeadController::class, 'saveMessageFeedback']);
        Route::get('/sales/prompt-variant', [LeadController::class, 'salesPromptVariant']);
        Route::post('/sales/prompt-variant', [LeadController::class, 'updateSalesPromptVariant']);
        Route::get('/leads/{sessionId}/followup-plans', [LeadController::class, 'followupPlans']);
        Route::post('/leads/{sessionId}/followup-plans', [LeadController::class, 'createFollowupPlan']);

        Route::get('/tags', [LeadController::class, 'allTags']);
        Route::post('/tags', [LeadController::class, 'createTag']);
        Route::get('/followup-plans', [LeadController::class, 'allFollowupPlans']);
        Route::get('/followup-plans/reasons', [LeadController::class, 'followupPlanReasons']);
        Route::post('/followup-plans/scheduler/run', [LeadController::class, 'runFollowupScheduler']);
        Route::post('/followup-plans/{id}/status', [LeadController::class, 'updateFollowupPlanStatus']);
        Route::post('/followup-plans/{id}/mark-sent', [LeadController::class, 'markFollowupPlanSent']);
        Route::delete('/followup-plans/{id}', [LeadController::class, 'deleteFollowupPlan']);

        Route::get('/learning/questions', [LearningController::class, 'index']);
        Route::get('/learning/questions/{id}', [LearningController::class, 'show']);
        Route::post('/learning/questions/{id}/resolve', [LearningController::class, 'resolve']);
        Route::get('/learning/reply-feedback', [LearningController::class, 'replyFeedbackIndex']);
        Route::get('/learning/reply-feedback/analytics', [LearningController::class, 'replyFeedbackAnalytics']);
        Route::post('/learning/reply-feedback/{id}/approval', [LearningController::class, 'updateReplyFeedbackApproval']);

        Route::get('/analytics/lead-source-behavior', [AnalyticsController::class, 'leadSourceBehavior']);
        Route::get('/analytics/tag-distribution', [AnalyticsController::class, 'tagDistribution']);
        Route::get('/analytics/conversion-by-tag', [AnalyticsController::class, 'conversionByTag']);
        Route::get('/analytics/followup-performance', [AnalyticsController::class, 'followupPerformance']);
        Route::get('/analytics/reply-source-breakdown', [AnalyticsController::class, 'replySourceBreakdown']);
        Route::get('/analytics/campaign-performance', [AnalyticsController::class, 'campaignPerformance']);
        Route::get('/analytics/unresolved-learning-trends', [AnalyticsController::class, 'unresolvedLearningTrends']);
        Route::get('/analytics/sales-stage-funnel', [AnalyticsController::class, 'salesStageFunnel']);
        Route::get('/analytics/sales-objection-breakdown', [AnalyticsController::class, 'salesObjectionBreakdown']);
        Route::get('/analytics/sales-cta-performance', [AnalyticsController::class, 'salesCtaPerformance']);
        Route::get('/analytics/sales-offer-analytics', [AnalyticsController::class, 'salesOfferAnalytics']);
        Route::get('/analytics/followup-timing-insights', [AnalyticsController::class, 'followupTimingInsights']);

        Route::get('/campaigns/types', [CampaignController::class, 'types']);
        Route::get('/campaigns', [CampaignController::class, 'index']);
        Route::post('/campaigns', [CampaignController::class, 'store']);
        Route::get('/campaigns/{id}', [CampaignController::class, 'show']);
        Route::get('/campaigns/{id}/recipients', [CampaignController::class, 'recipients']);
        Route::post('/promotions/tag-send', [CampaignController::class, 'sendTagPromotion']);

        Route::get('/outbound/types', [OutboundController::class, 'types']);
        Route::get('/outbound', [OutboundController::class, 'index']);
        Route::get('/cohorts/expired-clients', [CohortController::class, 'expiredClients']);
        Route::get('/cohorts/unsubscribed-registrations', [CohortController::class, 'unsubscribedRegistrations']);
        Route::post('/cohorts/{cohort}/followups', [CohortController::class, 'createFollowups']);
        Route::post('/cohorts/{cohort}/outbound', [CohortController::class, 'queueOutbound']);
        Route::post('/cohorts/{cohort}/sms', [CohortController::class, 'sendSms']);
        Route::get('/renewal-batches', [CohortController::class, 'listBatches']);
        Route::post('/renewal-batches', [CohortController::class, 'storeBatch']);
        Route::get('/renewal-batches/{id}', [CohortController::class, 'showBatch']);
        Route::post('/renewal-batches/{id}/run', [CohortController::class, 'runBatch']);
        Route::post('/renewal-batches/{id}/clone', [CohortController::class, 'cloneBatch']);
        Route::get('/renewal-batches/{id}/export', [CohortController::class, 'exportBatchRecipients']);
        Route::post('/renewal-batches/{id}/archive', [CohortController::class, 'archiveBatch']);
        Route::delete('/renewal-batches/{id}', [CohortController::class, 'destroyBatch']);

        Route::get('/training/{botType}', [TrainingController::class, 'index']);
        Route::post('/training/{botType}', [TrainingController::class, 'store']);
        Route::delete('/training/{botType}/{id}', [TrainingController::class, 'destroy']);
        Route::get('/knowledge/items', [KnowledgeController::class, 'index']);
        Route::post('/knowledge/items', [KnowledgeController::class, 'store']);
        Route::get('/knowledge/items/{id}', [KnowledgeController::class, 'show']);
        Route::patch('/knowledge/items/{id}', [KnowledgeController::class, 'update']);
        Route::delete('/knowledge/items/{id}', [KnowledgeController::class, 'destroy']);
        Route::get('/live-client-showcases', [LiveClientShowcaseController::class, 'index']);
        Route::post('/live-client-showcases', [LiveClientShowcaseController::class, 'store']);
        Route::get('/live-client-showcases/{id}', [LiveClientShowcaseController::class, 'show']);
        Route::patch('/live-client-showcases/{id}', [LiveClientShowcaseController::class, 'update']);
        Route::delete('/live-client-showcases/{id}', [LiveClientShowcaseController::class, 'destroy']);

        Route::get('/handoffs/{sessionId}', [HandoffController::class, 'show']);
        Route::post('/handoffs/{sessionId}/resolve', [HandoffController::class, 'resolve']);
        Route::post('/handoffs/{sessionId}/assign-bot', [HandoffController::class, 'assignBot']);
        Route::post('/handoffs/{sessionId}/messages', [HandoffController::class, 'sendMessage']);
        Route::get('/review/handoffs', [ReviewController::class, 'handoffs']);
        Route::get('/review/abusive', [ReviewController::class, 'abusive']);
        Route::get('/review/manual', [ReviewController::class, 'manual']);
        Route::get('/review/unclear', [ReviewController::class, 'unclear']);
        Route::get('/review/dropped', [ReviewController::class, 'dropped']);
        Route::post('/review/{sessionId}/assign-human', [ReviewController::class, 'assignHuman']);
        Route::post('/review/{sessionId}/disable-bot', [ReviewController::class, 'disableBot']);
        Route::post('/review/{sessionId}/enable-bot', [ReviewController::class, 'enableBot']);
        Route::post('/review/{sessionId}/resolve', [ReviewController::class, 'resolve']);
        Route::post('/review/{sessionId}/note', [ReviewController::class, 'note']);

    });
});

Route::post('/v1/address/easy-order/save', [UserController::class, 'saveaddress']);

Route::post('/v1/modules', [UserController::class, 'modules']);
Route::post('/v1/get-module-info', [UserController::class, 'getModuleInfo']);
Route::get('/v1/get-module/{id}', [UserController::class, 'getModuleById']);

Route::get('/v1/brand', [UserController::class, 'getBrand']);
Route::post('/v1/fileUpload', [MailController::class, 'fileUpload']);

Route::get('/v1/get/district', [DistrictController::class, 'getAllDistrict']);
Route::get('/v1/get/district/{id}', [DistrictController::class, 'getDistrictById']);


Route::middleware('auth:sanctum')->group(function () {
    Route::get('/v1/getuser', [LoginController::class, 'getuser']);
    Route::post('/v1/password-change', [UserController::class, 'changepass']);
    Route::post('/v1/user/updateprofile', [UserController::class, 'updateuser']);
    Route::post('/v1/order/cancel', [OrderController::class, 'cancelorder']);
    Route::post('/v1/getorder/details', [OrderController::class, 'orderdetails']);
    Route::post('/v1/getorder', [OrderController::class, 'getorder']);
    Route::post('/v1/verifyotp', [LoginController::class, 'verifyotp']);
    Route::post('/v1/logout', [LoginController::class, 'logout']);
    Route::get('/v1/admin/logout', [LoginController::class, 'admin_logout']);
    Route::post('/v1/review', [OrderController::class, 'review']);
    Route::post('/v1/address', [UserController::class, 'address']);
    Route::post('/v1/address/save', [UserController::class, 'saveaddress']);

    Route::post('/v1/address/edit', [UserController::class, 'updateaddress']);
    Route::post('/v1/address/delete', [UserController::class, 'deleteaddress']);
    Route::post('/v1/placeorder', [OrderController::class, 'placeorder']);
});



// Route::group(['middleware' => 'api'], function($router) {
//     Route::post('/register', [JWTController::class, 'register']);
//     Route::post('/login', [JWTController::class, 'login']);
//     Route::post('/logout', [JWTController::class, 'logout']);
//     Route::post('/refresh', [JWTController::class, 'refresh']);
//     Route::post('/profile', [JWTController::class, 'profile']);
// });


Route::get('/response', [ResponseController::class, 'index']);
Route::group(['prefix' => 'v1'], function () {
    // your routes here
    Route::post('auth/socia-id', [QuickLoginController::class, 'sociaId']);
    Route::post('auth/google/login', [QuickLoginController::class, 'googleLogin']);

    Route::get('/subdomain/name/validate', [SubdomainController::class, 'index']);
    Route::get('/getsearch', [SubdomainController::class, 'getsearch']);
    Route::get('/subdomain/header/name', [SubdomainController::class, 'sendheader']);
    Route::post('/login', [LoginController::class, 'index']);
    Route::post('/paymentlogin', [LoginController::class, 'paymentlogin']);
    Route::post('/register', [LoginController::class, 'register']);
    Route::post('/user/register', [LoginController::class, 'register']);
    Route::post('/getsubdomain/name', [SubdomainController::class, 'getsubdomainname']);
    Route::get('/get-domain/{name}/{section}', [SubdomainController::class, 'getDomainSection']);
    Route::post('/getsubdomain/data', [SubdomainController::class, 'getsubdomainnameNew']);
    Route::post('/brand', [SubdomainController::class, 'getAllBrandProducts']);
    Route::post('/getcatproducts', [SubdomainController::class, 'getcatproduct']);
    Route::post('/getsubcatproduct', [SubdomainController::class, 'getsubcatproduct']);
    Route::post('/gettagproduct', [SubdomainController::class, 'getTagProduct']);

    Route::post('/campaign', [SubdomainController::class, 'campaign']);

    Route::post('/product/search', [SubdomainController::class, 'productSearch']);

    Route::post('/verifycoupon', [SubdomainController::class, 'verifycoupon']);
    Route::post('/check/coupon-is-available', [SubdomainController::class, 'availableCoupon']);

    Route::post('/admin/verify-coupon', [SubdomainController::class, 'adminVerifyCoupon']);

    Route::post('/product-details', [SubdomainController::class, 'getdetails']);

    Route::post('/getcodeproduct', [PosController::class, 'getsearchproductbarcode']);

    Route::post('/change-password', [LoginController::class, 'changepass']);
    Route::post('/forget-pass', [LoginController::class, 'forget']);
    Route::post('/forget-verify', [LoginController::class, 'forgetverify']);
    Route::post('/user/details', [UserController::class, 'userdetails']);
    Route::get('/plan-details', [SubdomainController::class, 'plandetails']);
    Route::get('/homepage/layout', [SubdomainController::class, 'homepagelayout']);
    Route::post('/page', [SubdomainController::class, 'pages']);
    Route::post('/related-product', [SubdomainController::class, 'relatedproduct']);
    Route::post('/get/review', [SubdomainController::class, 'getreview']);
    Route::post('/get/offer/product', [SubdomainController::class, 'checkoffer']);
    Route::get('/shoppage/products', [SubdomainController::class, 'getshoppageproduct']);
    Route::get('/apps/url', [SubdomainController::class, 'appsurl']);
    Route::post('/page/payment', [PaymentPageController::class, 'index']);
    Route::post('/placeplan', [PaymentPageController::class, 'placeplan']);
    Route::post('/addons-buy', [PaymentPageController::class, 'addonsBuy']);
    Route::get('/addons', [PaymentPageController::class, 'addons']);
    Route::post('/payment-history', [PaymentPageController::class, 'paymentHistory']);
    Route::post('/checkactive', [PaymentPageController::class, 'activepage']);
    Route::post('/deactivestore', [PaymentPageController::class, 'deactivestore']);
    Route::get('/popup/image', [SubdomainController::class, 'popupimage']);
    Route::get('/getnotification', [SubdomainController::class, 'getnotification']);

    Route::post('/saveslider', [ImageController::class, 'saveslider']);
    Route::post('/savebanner', [ImageController::class, 'savebanner']);
    Route::post('/savetestimonials', [ImageController::class, 'savetestimonials']);
    Route::post('/savehs', [ImageController::class, 'savehs']);
    Route::post('/saveuserimage', [ImageController::class, 'saveuserimage']);
    Route::post('/savetoken', [ImageController::class, 'savetoken']);
    Route::post('/savemapp', [ImageController::class, 'savemapp']);
    Route::post('/savebrand', [ImageController::class, 'savebrand']);
    Route::post('/savecat', [ImageController::class, 'savecat']);
    Route::post('/saveproduct', [ImageController::class, 'saveproduct']);
    Route::get('/templates', [SubdomainController::class, 'templates']);
    Route::get('/checkout-page/form-field/{store}', [DesignController::class, 'getDesignCheckoutForm']);

    Route::post('/initialactiveplan', [PaymentPageController::class, 'initialactiveplan']);

    Route::post('/userinfo', [UserController::class, 'userinfo']);
    Route::post('/user-registration-email', [UserController::class, 'userRegistrationEmail']);

    Route::post('/users/checkotp', [UserController::class, 'checkotps']);
    Route::post('/user/resendotp', [UserController::class, 'rsendotps']);

    Route::post('/user/registration', [UserController::class, 'registers']);
    Route::post('/user/registration/check', [UserController::class, 'registerscheck']);

    Route::post('/getcatpos', [PosController::class, 'getcat']);
    Route::post('/getproducts', [PosController::class, 'getproducts']);
    Route::post('/addtocart', [PosController::class, 'addtocart']);
    Route::post('/getcarts', [PosController::class, 'getcarts']);
    Route::post('/incrementcart', [PosController::class, 'incrementcart']);
    Route::post('/decrementcart', [PosController::class, 'decrementcart']);
    Route::post('/removecart', [PosController::class, 'removecart']);
    Route::post('/addveritocart', [PosController::class, 'addveritocart']);
    Route::post('/getcatproduct', [PosController::class, 'getcatproduct']);
    Route::post('/getcustomer', [PosController::class, 'getcustomer']);
    Route::post('/posorder', [PosController::class, 'posorder']);
    Route::post('/posorderhold', [PosController::class, 'posorderhold']);
    Route::post('/getholdorders', [PosController::class, 'getholdorders']);
    Route::post('/holdorderproduct', [PosController::class, 'holdorderproduct']);
    Route::post('/deleteholdorder', [PosController::class, 'deleteholdorder']);
    Route::get('/getorderid', [PosController::class, 'getorderid']);
    Route::post('/editholdorders', [PosController::class, 'editholdorders']);
    Route::post('/getsearchproduct', [PosController::class, 'getsearchproduct']);
    Route::post('/getsearchproductbarcode', [PosController::class, 'getsearchproductbarcode']);

    Route::get('/digitaltimmer', [SubdomainController::class, 'digitaltimmer']);

    Route::post('/app-status', [SubdomainController::class, 'appStatus']);

    //Bolg
    Route::group(['prefix' => '/blog'], function () {
        Route::get('/get/{store?}', [AdminBlogController::class, 'index']);
        Route::get('/details/{slug}', [AdminBlogController::class, 'show']);

        Route::get('/types/{id?}', [AdminBlogController::class, 'blogTypes']);

        Route::get('/popular', [AdminBlogController::class, 'popularBlog']);

        Route::get('/site-map', [AdminBlogController::class, 'siteMap']);
    });
    //End Blog

    // Announcement
    Route::post('/get-announcement', [AnnouncementController::class, 'index']);

    //PSE Marketplace
    Route::group(['prefix' => '/pse'], function () {
        Route::group(['prefix' => '/products'], function () {
            Route::get('/', [MarketplaceController::class, 'index']);
            Route::get('/categories', [MarketplaceController::class, 'getAllCategories']);
            Route::get('/search', [MarketplaceController::class, 'searchProductByName']);
            Route::get('/product-by-category', [MarketplaceController::class, 'searchProductIdAndName']);
            Route::post('/visitor', [MarketplaceController::class, 'visitorCounter']);
            Route::get('/category', [MarketplaceController::class, 'categoryProduct']);
            Route::get('/top-pik-products', [MarketplaceController::class, 'topPicProduct']);
        });

        Route::group(['prefix' => '/ads'], function () {
            Route::get('/', [PseAdsController::class, 'index']);
        });
    });
    //PSE Marketplace end

    // Ebitans Analytics hk
    Route::get('/ebi-analytics', [EbtAnalyticsController::class, 'index']);
    Route::post('/ebi-analytics/store', [EbtAnalyticsController::class, 'store']);
    // End Ebitans Analytics hk


    // NewsLetter hk
    Route::get('/news-latter', [NewsLetterController::class, 'index']);
    Route::post('/news-latter/store', [NewsLetterController::class, 'store']);

    Route::get('/admin-noti/{id}', [NewsLetterController::class, 'getNotiFica']);
    // End NewsLetter

    // User
    Route::get('/bkash/checkout-url/orderPay', [BkashController::class, 'orderPay'])->name('bkash.payment');
    Route::get('/admin/bkash/checkout-url/orderPay', [AdminBkashController::class, 'orderPay'])->name('admin.bkash.payment');

    Route::post('/booking-from', [BookingController::class, 'index']);

    //api theme controller
    Route::controller(ThemeController::class)->group(function () {
        // header setting
        Route::post('/header-settings', 'headerSettings');

        // layout
        Route::prefix('/layout')->group(function () {
            Route::post('/product', 'layoutProducts');
            Route::get('/products/{name}', 'getProductForLayout');
        });
    });

    //product_affiliate
    Route::controller(ProductAffiliateController::class)->prefix('/customer-affiliate')->group(function () {
        Route::post('/register', 'register');
        Route::post('/withdraw-requests', 'getWithdrawRequest');
        Route::post('/withdraw-requests/pending', 'getWithdrawRequest');
        Route::post('/withdraw-requests/approved', 'getWithdrawRequest');
        Route::post('/withdraw-requests/rejected', 'getWithdrawRequest');
        Route::post('/create/withdraw-requests', 'createWithdrawRequest');
        Route::post('/order-list', 'getAffiliateOrderDetails');
    });

});
