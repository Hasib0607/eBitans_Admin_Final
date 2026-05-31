<?php

$liveClientShowcasePath = trim((string) env('EBITANS_LARAVEL_LIVE_CLIENT_SHOWCASE_PATH', 'whatsapp/live-client-showcase'), '/');
if ($liveClientShowcasePath === '') {
    $liveClientShowcasePath = 'whatsapp/live-client-showcase';
}

return [
    'frontend_url' => env('WHATSAPP_AUTOMATION_FRONTEND_URL', 'http://localhost:5173'),
    'bot_api_url' => env('WHATSAPP_BOT_API_URL', ''),
    'bot_admin_token' => env('WHATSAPP_BOT_ADMIN_TOKEN', ''),
    'gateway_api_url' => env('WHATSAPP_GATEWAY_API_URL', ''),
    'gateway_api_secret' => env('WHATSAPP_GATEWAY_API_SECRET', ''),
    'gateway_tenant_id' => env('WHATSAPP_GATEWAY_TENANT_ID', ''),
    'gateway_timeout_seconds' => (int) env('WHATSAPP_GATEWAY_TIMEOUT_SECONDS', 30),
    'gateway_auto_reply_enabled' => filter_var(env('WHATSAPP_GATEWAY_AUTO_REPLY_ENABLED', true), FILTER_VALIDATE_BOOL, FILTER_NULL_ON_FAILURE) ?? true,
    'react_token_secret' => env('WHATSAPP_REACT_TOKEN_SECRET', ''),
    'react_token_ttl_minutes' => (int) env('WHATSAPP_REACT_TOKEN_TTL_MINUTES', 30),
    'react_code_ttl_minutes' => (int) env('WHATSAPP_REACT_CODE_TTL_MINUTES', 5),
    'laravel_api_token' => env('EBITANS_LARAVEL_API_TOKEN', ''),
    'live_client_showcase_path' => $liveClientShowcasePath,
    'react_cookie_name' => env('WHATSAPP_REACT_COOKIE_NAME', 'whatsapp_react_session'),
    'react_cookie_domain' => env('WHATSAPP_REACT_COOKIE_DOMAIN'),
    'react_cookie_secure' => filter_var(env('WHATSAPP_REACT_COOKIE_SECURE', true), FILTER_VALIDATE_BOOL, FILTER_NULL_ON_FAILURE) ?? true,
    'react_cookie_same_site' => env('WHATSAPP_REACT_COOKIE_SAME_SITE', 'none'),
];
