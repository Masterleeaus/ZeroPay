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

];
