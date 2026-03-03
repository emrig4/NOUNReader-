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
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],
'paystack' => [
    'secret' => env('PAYSTACK_SECRET_KEY', 'sk_test_9820f9449e8a5eb6efcd68c43560f8fc70bd805d'),
    'public' => env('PAYSTACK_PUBLIC_KEY', 'pk_test_your_public_key'),
    'timeout' => 10,
],

'paystack' => [
    'model' => env('PAYSTACK_MODEL', 'App\Models\User'),
    'key' => env('PAYSTACK_PUBLIC_KEY'),
    'secret' => env('PAYSTACK_SECRET_KEY'),
    'payment_url' => env('PAYSTACK_PAYMENT_URL', 'https://api.paystack.co'),
    'merchant_email' => env('PAYSTACK_MERCHANT_EMAIL'),
],

'paystacksubscription' => [
    'model' => env('PAYSTACK_MODEL', 'App\Models\User'),
    'key' => env('PAYSTACK_PUBLIC_KEY'),
    'secret' => env('PAYSTACK_SECRET_KEY'),
    'payment_url' => env('PAYSTACK_PAYMENT_URL', 'https://api.paystack.co'),
    'merchant_email' => env('PAYSTACK_MERCHANT_EMAIL'),
],

'recaptcha' => [
    'site_key' => env('RECAPTCHA_SITE_KEY'),
    'secret_key' => env('RECAPTCHA_SECRET_KEY'),
],

];
