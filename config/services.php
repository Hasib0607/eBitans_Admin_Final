<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],
    'facebook' => [
        'client_id' => env('FACEBOOK_APP_ID'),
        'client_secret' => env('FACEBOOK_APP_SECRET'),
        'redirect' => env('FACEBOOK_REDIRECT'),
    ],
    'google' => [
        'client_id' => env('google_CLIENT_ID'),
        'client_secret' => env('google_CLIENT_SECRET'),
        'redirect' => env('google_redirect'),
    ],

    'account_domain' => [
        'base_url' => env('ACCOUNT_DOMAIN_API_BASE_URL', 'http://72.60.235.117:4000'),
        'token' => env('ACCOUNT_DOMAIN_API_TOKEN'),
        'project_token' => env('ACCOUNT_PROJECT_DOMAIN_API_TOKEN'),
        'timeout' => env('ACCOUNT_DOMAIN_API_TIMEOUT', 30),
        'force_ssl' => env('ACCOUNT_DOMAIN_FORCE_SSL', true),
        'hosting_mode' => env('ACCOUNT_DOMAIN_HOSTING_MODE', 'PUBLIC_HTML'),
        'document_root' => env('ACCOUNT_DOMAIN_DOCUMENT_ROOT', 'public_html'),
        'csrf_token' => env('ACCOUNT_DOMAIN_CSRF_TOKEN', ''),
        'csrf_cookie' => env('ACCOUNT_DOMAIN_CSRF_COOKIE', ''),
    ],

];
