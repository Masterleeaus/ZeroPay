<?php

namespace Modules\ZeroPayModule\Services;

use Modules\ZeroPayModule\Exceptions\GatewayNotFoundException;
use Modules\ZeroPayModule\Services\Contracts\GatewayContract;

class GatewayRegistry
{
    /**
     * @var array<string, GatewayContract>
     */
    protected array $gateways = [];

    public function register(string $name, GatewayContract $gateway): void
    {
        $this->gateways[strtolower($name)] = $gateway;
    }

    public function resolve(string $name): GatewayContract
    {
        $key = strtolower($name);

        if (!isset($this->gateways[$key])) {
            throw GatewayNotFoundException::forName($name);
        }

        return $this->gateways[$key];
    }

    /**
     * @return array<int, string>
     */
    public function available(): array
    {
        $available = [];

        foreach ($this->gateways as $name => $gateway) {
            if ($gateway->isAvailable()) {
                $available[] = $name;
            }
        }

        return $available;
    }
}
