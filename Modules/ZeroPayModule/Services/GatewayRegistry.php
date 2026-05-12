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
        return array_values(array_map(
            static fn (string $name) => $name,
            array_keys(array_filter(
                $this->gateways,
                static fn (GatewayContract $gateway): bool => $gateway->isAvailable()
            ))
        ));
    }
}
