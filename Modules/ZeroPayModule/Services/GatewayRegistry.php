<?php

namespace Modules\ZeroPayModule\Services;

use Illuminate\Container\Container;
use Modules\ZeroPayModule\Settings\ZeroPaySettings;

/**
 * Central gateway availability registry.
 *
 * Checks the persisted ZeroPaySettings first (if available), then falls back
 * to the module config array so the registry works even before the settings
 * migration has been run.
 */
class GatewayRegistry
{
    /** @var array<string, string> Maps gateway key → ZeroPaySettings property name */
    protected const SETTING_MAP = [
        'payid'         => 'gateway_payid_enabled',
        'bank_transfer' => 'gateway_bank_transfer_enabled',
        'stripe'        => 'gateway_stripe_enabled',
        'paypal'        => 'gateway_paypal_enabled',
        'cryptomus'     => 'gateway_cryptomus_enabled',
        'cash'          => 'gateway_cash_enabled',
    ];

    public function __construct(protected ?ZeroPaySettings $settings = null) {}

    /**
     * Returns true when the given gateway is enabled.
     */
    public function isAvailable(string $gateway): bool
    {
        $property = self::SETTING_MAP[$gateway] ?? null;

        if ($property !== null && $this->settings !== null) {
            return (bool) ($this->settings->{$property} ?? false);
        }

        // Fall back to module config
        return (bool) $this->configValue("zeropay-module.gateways.{$gateway}.enabled", false);
    }

    /**
     * Returns all gateway keys that are currently enabled.
     *
     * @return array<string>
     */
    public function available(): array
    {
        return array_values(
            array_filter(array_keys(self::SETTING_MAP), fn (string $key) => $this->isAvailable($key))
        );
    }

    /**
     * Returns every registered gateway key regardless of enabled state.
     *
     * @return array<string>
     */
    public function all(): array
    {
        return array_keys(self::SETTING_MAP);
    }

    protected function configValue(string $key, mixed $default = null): mixed
    {
        try {
            $container = Container::getInstance();

            if ($container !== null && $container->bound('config')) {
                return $container->make('config')->get($key, $default);
            }
        } catch (\Throwable) {
            // container not available (e.g. standalone unit test)
        }

        return $default;
    }
}
