<?php

namespace Modules\ZeroPayModule\Services;

use InvalidArgumentException;
use Modules\ZeroPayModule\Adapters\BankTransferGatewayAdapter;
use Modules\ZeroPayModule\Adapters\CashGatewayAdapter;
use Modules\ZeroPayModule\Adapters\CryptomusGatewayAdapter;
use Modules\ZeroPayModule\Adapters\PayIdGatewayAdapter;
use Modules\ZeroPayModule\Adapters\PayPalGatewayAdapter;
use Modules\ZeroPayModule\Adapters\StripeGatewayAdapter;
use Modules\ZeroPayModule\Contracts\GatewayContract;

class GatewayFactory
{
    protected array $adapters = [
        'payid'         => PayIdGatewayAdapter::class,
        'bank_transfer' => BankTransferGatewayAdapter::class,
        'cash'          => CashGatewayAdapter::class,
        'stripe'        => StripeGatewayAdapter::class,
        'paypal'        => PayPalGatewayAdapter::class,
        'cryptomus'     => CryptomusGatewayAdapter::class,
    ];

    public function make(string $gateway): GatewayContract
    {
        if (!isset($this->adapters[$gateway])) {
            throw new InvalidArgumentException("Unsupported gateway: {$gateway}");
        }

        return app($this->adapters[$gateway]);
    }

    public function supported(): array
    {
        return array_keys($this->adapters);
    }
}
