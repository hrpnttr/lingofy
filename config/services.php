<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Resend, Postmark, AWS, and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    // Shared secret sent to lingofy-backend on every request (X-Service-Token).
    'backend' => [
        'token' => env('BACKEND_SERVICE_TOKEN'),
    ],

    // Course payments. Keep disabled until a payment provider is integrated
    // in App\Http\Controllers\CheckoutController.
    'payments' => [
        'enabled' => (bool) env('PAYMENTS_ENABLED', false),
        'provider' => env('PAYMENTS_PROVIDER'),
        'currency' => env('PAYMENTS_CURRENCY', 'USD'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

];
