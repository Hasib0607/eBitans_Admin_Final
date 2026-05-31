<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array<int, class-string|string>
     */
    protected $middleware = [
        // \App\Http\Middleware\TrustHosts::class,
        \App\Http\Middleware\TrustProxies::class,
        \Illuminate\Http\Middleware\HandleCors::class,
        \App\Http\Middleware\PreventRequestsDuringMaintenance::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,

        // \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array<string, array<int, class-string|string>>
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            // \Illuminate\Session\Middleware\AuthenticateSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],

        'api' => [
            \App\Http\Middleware\FixAuthorizationHeader::class,
            // \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            // 'throttle:api',
            'throttle:600,1',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array<string, class-string|string>
     */
    protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
        'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
        'admin' => \App\Http\Middleware\Admin::class,
        'superadmin' => \App\Http\Middleware\Superadmin::class,
        'user' => \App\Http\Middleware\User::class,
        'customer' => \App\Http\Middleware\Customer::class,
        'staff' => \App\Http\Middleware\Staff::class,
        'otpverify' => \App\Http\Middleware\Otpverify::class,
        'checkplan' => \App\Http\Middleware\Checkplan::class,
        'store' => \App\Http\Middleware\Store::class,
        'activestore' => \App\Http\Middleware\Activestore::class,
        'posplan' => \App\Http\Middleware\Posplan::class,
        'digitalplan' => \App\Http\Middleware\Digitalplan::class,
        'websiteplan' => \App\Http\Middleware\Websiteplan::class,
        'websitepos' => \App\Http\Middleware\Websitepos::class,
        'checkSms' => \App\Http\Middleware\CheckSms::class,
        'affiliate' => \App\Http\Middleware\Affiliate::class,
        'isAdminOrSuperAdmin' => \App\Http\Middleware\CheckAdminAndSuperAdmin::class,
        'isModulusAccess' => \App\Http\Middleware\IsModulusAccess::class,
        'affiliateUpdateProfile' => \App\Http\Middleware\AffiliateProfileUpdateCheck::class,
        'pse.domainCheck' => \App\Http\Middleware\PSEWhitelistedDomain::class,
        'whatsapp.access' => \App\Http\Middleware\EnsureWhatsAppAutomationAccess::class,
        'whatsapp.react' => \App\Http\Middleware\VerifyWhatsAppReactToken::class,
        'ebitans.api.token' => \App\Http\Middleware\VerifyEbitansLaravelApiToken::class,
    ];
}
