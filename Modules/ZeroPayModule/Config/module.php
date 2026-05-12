<?php

use Modules\ZeroPayModule\Adapters\DefaultGatewayAdapter;

return [

    /*
    |--------------------------------------------------------------------------
    | ZeroPayModule Configuration
    |--------------------------------------------------------------------------
    |
    | Module-level configuration values for ZeroPay. Override these values
    | in your application's config/zeropay-module.php after publishing.
    |
    */

    'name' => 'ZeroPayModule',

    /*
    |--------------------------------------------------------------------------
    | Default Gateway
    |--------------------------------------------------------------------------
    |
    | The gateway adapter that will be resolved when GatewayContract is
    | injected. Must implement Modules\ZeroPayModule\Contracts\GatewayContract.
    |
    */
    'gateway' => DefaultGatewayAdapter::class,

    /*
    |--------------------------------------------------------------------------
    | PayID Configuration
    |--------------------------------------------------------------------------
    |
    | The PayID address used as the payment destination for NPP/PayID payments.
    | Override via ZEROPAY_PAYID in your .env file.
    |
    */
    'payid' => env('ZEROPAY_PAYID', 'payments@merchant.com'),

    /*
    |--------------------------------------------------------------------------
    | Merchant Name
    |--------------------------------------------------------------------------
    |
    | The merchant name displayed on QR code payloads.
    |
    */
    'merchant_name' => env('ZEROPAY_MERCHANT_NAME', 'ZeroPay Merchant'),

    /*
    |--------------------------------------------------------------------------
    | API Rate Limiting
    |--------------------------------------------------------------------------
    */
    'rate_limit' => 60,

    /*
    |--------------------------------------------------------------------------
    | VAPID Keys — Web Push Notifications
    |--------------------------------------------------------------------------
    |
    | Generate a key pair with:
    |   vendor/bin/web-push generate-vapid-keys
    | or any online VAPID key generator.
    |
    | Store the values in your application .env file:
    |   VAPID_PUBLIC_KEY=
    |   VAPID_PRIVATE_KEY=
    |   VAPID_SUBJECT=mailto:admin@zeropay.io
    |
    */
    'vapid' => [
        'subject' => env('VAPID_SUBJECT', 'mailto:admin@zeropay.io'),
        'public_key' => env('VAPID_PUBLIC_KEY', ''),
        'private_key' => env('VAPID_PRIVATE_KEY', ''),
    ],

];
