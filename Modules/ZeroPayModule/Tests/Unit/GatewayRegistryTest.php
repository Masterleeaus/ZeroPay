<?php

namespace Modules\ZeroPayModule\Tests\Unit;

use Modules\ZeroPayModule\Services\GatewayRegistry;
use PHPUnit\Framework\TestCase;

class GatewayRegistryTest extends TestCase
{
    public function test_all_returns_all_six_gateway_keys(): void
    {
        $registry = new GatewayRegistry(null);

        $this->assertSame(
            ['payid', 'bank_transfer', 'stripe', 'paypal', 'cryptomus', 'cash'],
            $registry->all()
        );
    }

    public function test_is_available_returns_false_when_no_settings_and_no_config(): void
    {
        $registry = new GatewayRegistry(null);

        $this->assertFalse($registry->isAvailable('stripe'));
        $this->assertFalse($registry->isAvailable('paypal'));
        $this->assertFalse($registry->isAvailable('payid'));
    }

    public function test_available_is_empty_when_all_disabled(): void
    {
        $registry = new GatewayRegistry(null);

        $this->assertSame([], $registry->available());
    }

    public function test_is_available_returns_false_for_unknown_gateway(): void
    {
        $registry = new GatewayRegistry(null);

        $this->assertFalse($registry->isAvailable('unknown_gateway'));
    }

    public function test_gateway_registry_reads_from_settings_when_provided(): void
    {
        $settings = new class
        {
            public bool $gateway_cash_enabled = true;

            public bool $gateway_payid_enabled = false;

            public bool $gateway_bank_transfer_enabled = false;

            public bool $gateway_stripe_enabled = false;

            public bool $gateway_paypal_enabled = false;

            public bool $gateway_cryptomus_enabled = false;
        };

        // We need to create the registry with a real ZeroPaySettings, but since
        // we cannot instantiate it without the framework, we test via a stub
        // that mimics the same property structure.
        // The GatewayRegistry only reads named public properties on the settings object.
        $registry = $this->buildRegistryWithSettings($settings);

        $this->assertTrue($registry->isAvailable('cash'));
        $this->assertFalse($registry->isAvailable('stripe'));
        $this->assertSame(['cash'], $registry->available());
    }

    public function test_gateway_registry_can_report_multiple_enabled_gateways(): void
    {
        $settings = new class
        {
            public bool $gateway_cash_enabled = true;

            public bool $gateway_payid_enabled = false;

            public bool $gateway_bank_transfer_enabled = true;

            public bool $gateway_stripe_enabled = false;

            public bool $gateway_paypal_enabled = false;

            public bool $gateway_cryptomus_enabled = false;
        };

        $registry = $this->buildRegistryWithSettings($settings);

        $available = $registry->available();

        $this->assertContains('cash', $available);
        $this->assertContains('bank_transfer', $available);
        $this->assertCount(2, $available);
    }

    /**
     * Build a GatewayRegistry whose SETTING_MAP reads from a plain object that
     * has the same public properties as ZeroPaySettings, bypassing the framework.
     */
    private function buildRegistryWithSettings(object $settings): GatewayRegistry
    {
        // Use reflection to inject the stub as the $settings dependency.
        $registry = new GatewayRegistry(null);

        $ref = new \ReflectionProperty(GatewayRegistry::class, 'settings');
        $ref->setAccessible(true);
        $ref->setValue($registry, $settings);

        return $registry;
    }
}
