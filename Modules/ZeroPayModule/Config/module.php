<?php

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

    'name'    => 'ZeroPayModule',

    /*
    |--------------------------------------------------------------------------
    | Default Gateway
    |--------------------------------------------------------------------------
    |
    | The gateway adapter that will be resolved when GatewayContract is
    | injected. Must implement Modules\ZeroPayModule\Contracts\GatewayContract.
    |
    */
    'gateway' => \Modules\ZeroPayModule\Adapters\DefaultGatewayAdapter::class,

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
        'subject'     => env('VAPID_SUBJECT', 'mailto:admin@zeropay.io'),
        'public_key'  => env('VAPID_PUBLIC_KEY', ''),
        'private_key' => env('VAPID_PRIVATE_KEY', ''),
    ],

];
