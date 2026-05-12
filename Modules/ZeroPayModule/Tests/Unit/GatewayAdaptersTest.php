<?php

namespace Modules\ZeroPayModule\Tests\Unit;

use Modules\ZeroPayModule\Adapters\CashGatewayAdapter;
use Modules\ZeroPayModule\Adapters\CryptomusGatewayAdapter;
use Modules\ZeroPayModule\Adapters\PayPalGatewayAdapter;
use Modules\ZeroPayModule\Adapters\StripeGatewayAdapter;
use PHPUnit\Framework\TestCase;

class GatewayAdaptersTest extends TestCase
{
    public function test_stripe_adapter_honours_enabled_flag_and_returns_client_secret(): void
    {
        $captured = [];
        $adapter = new StripeGatewayAdapter(
            ['enabled' => true, 'secret' => 'sk_test_123'],
            function (array $payload, array $config) use (&$captured): array {
                $captured = compact('payload', 'config');

                return ['id' => 'pi_123', 'client_secret' => 'secret_123'];
            }
        );

        $this->assertTrue($adapter->isAvailable());

        $payment = $adapter->createPayment([
            'amount' => 10.50,
            'currency' => 'AUD',
            'session_token' => 'session_123',
        ]);

        $this->assertSame('stripe', $payment['gateway']);
        $this->assertSame('pi_123', $payment['reference']);
        $this->assertSame('secret_123', $payment['client_secret']);
        $this->assertSame(1050, $captured['payload']['amount']);
        $this->assertSame('sk_test_123', $captured['config']['secret']);
        $this->assertFalse((new StripeGatewayAdapter(['enabled' => false]))->isAvailable());
    }

    public function test_stripe_webhook_signature_verification_maps_completed_status(): void
    {
        $payload = json_encode([
            'id' => 'evt_123',
            'type' => 'payment_intent.succeeded',
            'data' => [
                'object' => [
                    'id' => 'pi_123',
                ],
            ],
        ]);

        $this->assertIsString($payload);

        $secret = 'whsec_test_secret';
        $timestamp = time();
        $signature = hash_hmac('sha256', $timestamp.'.'.$payload, $secret);

        $adapter = new StripeGatewayAdapter([
            'enabled' => true,
            'webhook_secret' => $secret,
        ]);

        $result = $adapter->handleWebhook([
            'payload' => $payload,
            'signature' => "t={$timestamp},v1={$signature}",
        ]);

        $this->assertTrue($result['validated']);
        $this->assertSame('completed', $result['status']);
        $this->assertSame('pi_123', $result['reference']);
    }

    public function test_paypal_adapter_uses_existing_config_shape_and_returns_approval_url(): void
    {
        $captured = [];
        $adapter = new PayPalGatewayAdapter(
            [
                'enabled' => true,
                'mode' => 'sandbox',
                'client_id' => 'client-id',
                'client_secret' => 'client-secret',
            ],
            [
                'mode' => 'live',
                'sandbox' => ['client_id' => 'old', 'client_secret' => 'old-secret'],
                'currency' => 'USD',
                'notify_url' => 'https://example.test/paypal/ipn',
                'locale' => 'en_US',
                'validate_ssl' => true,
            ],
            function (array $providerConfig, array $orderPayload) use (&$captured): array {
                $captured = compact('providerConfig', 'orderPayload');

                return [
                    'id' => 'ORDER-123',
                    'links' => [
                        ['rel' => 'approve', 'href' => 'https://paypal.test/approve/ORDER-123'],
                    ],
                ];
            }
        );

        $payment = $adapter->createPayment([
            'amount' => 25.00,
            'currency' => 'USD',
            'session_token' => 'session_456',
        ]);

        $this->assertTrue($adapter->isAvailable());
        $this->assertSame('https://paypal.test/approve/ORDER-123', $payment['approval_url']);
        $this->assertSame('sandbox', $captured['providerConfig']['mode']);
        $this->assertSame('client-id', $captured['providerConfig']['sandbox']['client_id']);
        $this->assertSame('https://example.test/paypal/ipn', $captured['providerConfig']['notify_url']);
        $this->assertFalse((new PayPalGatewayAdapter(['enabled' => false], []))->isAvailable());
    }

    public function test_paypal_webhook_uses_verifier_result(): void
    {
        $adapter = new PayPalGatewayAdapter(
            ['enabled' => true],
            ['mode' => 'sandbox'],
            null,
            static fn (array $payload, array $providerConfig): bool => true
        );

        $result = $adapter->handleWebhook([
            'event_type' => 'PAYMENT.CAPTURE.COMPLETED',
            'resource' => [
                'id' => 'CAPTURE-123',
                'status' => 'COMPLETED',
            ],
        ]);

        $this->assertTrue($result['validated']);
        $this->assertSame('completed', $result['status']);
        $this->assertSame('CAPTURE-123', $result['reference']);
    }

    public function test_cryptomus_adapter_returns_invoice_url_and_verifies_signature(): void
    {
        $captured = [];
        $adapter = new CryptomusGatewayAdapter(
            [
                'enabled' => true,
                'merchant_id' => 'merchant-123',
                'api_key' => 'api-key',
                'webhook_secret' => 'webhook-secret',
            ],
            function (string $endpoint, array $payload, array $headers) use (&$captured): array {
                $captured = compact('endpoint', 'payload', 'headers');

                return [
                    'result' => [
                        'uuid' => 'invoice-123',
                        'url' => 'https://cryptomus.test/invoice/invoice-123',
                    ],
                ];
            }
        );

        $payment = $adapter->createPayment([
            'amount' => 50.00,
            'currency' => 'USD',
            'session_token' => 'session_789',
        ]);

        $this->assertTrue($adapter->isAvailable());
        $this->assertSame('/v1/invoice/create', $captured['endpoint']);
        $this->assertSame('merchant-123', $captured['headers']['merchant']);
        $this->assertSame('https://cryptomus.test/invoice/invoice-123', $payment['invoice_url']);

        $webhookPayload = json_encode([
            'uuid' => 'invoice-123',
            'order_id' => 'session_789',
            'status' => 'paid',
        ]);

        $this->assertIsString($webhookPayload);

        $result = $adapter->handleWebhook([
            'payload' => $webhookPayload,
            'signature' => hash_hmac('sha256', $webhookPayload, 'webhook-secret'),
        ]);

        $this->assertTrue($result['validated']);
        $this->assertSame('completed', $result['status']);
        $this->assertFalse((new CryptomusGatewayAdapter(['enabled' => false]))->isAvailable());
    }

    public function test_cash_adapter_marks_payment_pending_admin_confirmation(): void
    {
        $adapter = new CashGatewayAdapter(['enabled' => true]);

        $payment = $adapter->createPayment([
            'session_token' => 'cash-session-123',
        ]);

        $verification = $adapter->verifyPayment('cash-session-123');

        $this->assertTrue($adapter->isAvailable());
        $this->assertSame('cash-session-123', $payment['session_reference']);
        $this->assertTrue($payment['requires_admin_confirmation']);
        $this->assertSame('pending', $verification['status']);
        $this->assertTrue($verification['requires_admin_confirmation']);
        $this->assertFalse((new CashGatewayAdapter(['enabled' => false]))->isAvailable());
    }
}
