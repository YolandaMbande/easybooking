<?php

return [
    /*
    |--------------------------------------------------------------------------
    | PayFast Credentials
    |--------------------------------------------------------------------------
    |
    | These credentials are provided by PayFast when you set up your account.
    | Add your Merchant ID, Merchant Key, and optional passphrase.
    | Use test mode for sandbox testing.
    |
    */

    'merchant_id' => env('PAYFAST_MERCHANT_ID', '25205661'), // Default is sandbox ID
    'merchant_key' => env('PAYFAST_MERCHANT_KEY', 'h1ei3fydy87yc'), // Default is sandbox key
    'passphrase' => env('PAYFAST_PASSPHRASE', ''), // Leave blank if not set
    'test_mode' => env('PAYFAST_TEST_MODE', true), // True for sandbox mode

    /*
    |--------------------------------------------------------------------------
    | PayFast URLs
    |--------------------------------------------------------------------------
    |
    | Set your success, cancel, and notify URLs. These are used to handle
    | payment responses from PayFast.
    |
    */

    'success_url' => env('PAYFAST_SUCCESS_URL', '/payment/success'),
    'cancel_url' => env('PAYFAST_CANCEL_URL', '/payment/cancel'),
    'notify_url' => env('PAYFAST_NOTIFY_URL', '/payment/notify'),
];
