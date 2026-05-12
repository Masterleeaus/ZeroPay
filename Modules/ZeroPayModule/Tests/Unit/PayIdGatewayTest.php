<?php

namespace Modules\ZeroPayModule\Tests\Unit;

use Modules\ZeroPayModule\Models\ZeroPayQrCode;
use Modules\ZeroPayModule\Models\ZeroPaySession;
use Modules\ZeroPayModule\Services\Gateways\PayIdGateway;
use Modules\ZeroPayModule\Services\QrCodeService;
use Modules\ZeroPayModule\ValueObjects\GatewayResponse;
use Modules\ZeroPayModule\ValueObjects\WebhookResult;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class PayIdGatewayTest extends TestCase
{
    private QrCodeService&MockObject $qrCodeService;

    private PayIdGateway $gateway;

    protected function setUp(): void
    {
        parent::setUp();
        $this->qrCodeService = $this->createMock(QrCodeService::class);
        $this->gateway = new PayIdGateway($this->qrCodeService);
    }

    public function test_create_payment_without_session_model_returns_payid_response(): void
    {
        // When no DB session can be found (id = null), it falls back to session_token
        $session = [
            'id' => null,
            'session_token' => 'test-token-123',
            'company_id' => 1,
        ];

        // QrCodeService should NOT be called when session model cannot be found
        $this->qrCodeService->expects($this->never())->method('generateForSession');

        $result = $this->gateway->createPayment($session);

        $this->assertSame('pending', $result['status']);
        $this->assertSame('payid', $result['gateway']);
        $this->assertSame('test-token-123', $result['reference']);
        $this->assertArrayHasKey('pay_id', $result);
        $this->assertArrayHasKey('redirect_url', $result);
        $this->assertStringContainsString('test-token-123', $result['redirect_url']);
    }

    public function test_create_payment_with_session_model_calls_qr_code_service(): void
    {
        $session = new ZeroPaySession([
            'id' => 42,
            'company_id' => 1,
            'session_token' => 'qr-session-token',
            'amount' => '100.00',
            'currency' => 'AUD',
        ]);
        $session->id = 42;

        $qrCode = new ZeroPayQrCode;
        $qrCode->reference = 'qr-session-token';

        $this->qrCodeService
            ->expects($this->once())
            ->method('generateForSession')
            ->with($this->identicalTo($session))
            ->willReturn($qrCode);

        $result = $this->gateway->createPayment($session->toArray());

        $this->assertSame('pending', $result['status']);
        $this->assertSame('payid', $result['gateway']);
        $this->assertArrayHasKey('pay_id', $result);
        $this->assertArrayHasKey('redirect_url', $result);
    }

    public function test_verify_payment_returns_pending_when_no_completed_transaction(): void
    {
        $result = $this->gateway->verifyPayment('ref-abc');

        $this->assertSame('payid', $result['gateway']);
        $this->assertSame('ref-abc', $result['reference']);
        $this->assertArrayHasKey('status', $result);
    }

    public function test_handle_webhook_returns_processed_result(): void
    {
        $payload = ['reference' => 'payid_ref_001', 'amount' => 50.00];

        $result = $this->gateway->handleWebhook($payload);

        $this->assertTrue($result['processed']);
        $this->assertSame('payid', $result['gateway']);
        $this->assertSame($payload, $result['payload']);
    }

    public function test_handle_webhook_without_reference_still_succeeds(): void
    {
        $payload = ['event' => 'payment.confirmed'];

        $result = $this->gateway->handleWebhook($payload);

        $this->assertTrue($result['processed']);
        $this->assertSame('payid', $result['gateway']);
    }

    public function test_calculate_fee_returns_zero(): void
    {
        $this->assertSame(0.0, $this->gateway->calculateFee(100.0));
        $this->assertSame(0.0, $this->gateway->calculateFee(0.0));
    }

    public function test_refund_payment_returns_refunded_status(): void
    {
        $result = $this->gateway->refundPayment('txn-001');

        $this->assertSame('refunded', $result['status']);
        $this->assertSame('txn-001', $result['transaction_id']);
        $this->assertSame('payid', $result['gateway']);
    }

    public function test_gateway_response_value_object_to_array(): void
    {
        $response = new GatewayResponse(
            status: 'pending',
            gateway: 'payid',
            reference: 'ref-001',
            data: ['pay_id' => 'test@merchant.com'],
        );

        $array = $response->toArray();

        $this->assertSame('pending', $array['status']);
        $this->assertSame('payid', $array['gateway']);
        $this->assertSame('ref-001', $array['reference']);
        $this->assertSame('test@merchant.com', $array['pay_id']);
    }

    public function test_webhook_result_value_object_to_array(): void
    {
        $result = new WebhookResult(
            processed: true,
            gateway: 'payid',
            payload: ['ref' => 'abc'],
        );

        $array = $result->toArray();

        $this->assertTrue($array['processed']);
        $this->assertSame('payid', $array['gateway']);
        $this->assertSame(['ref' => 'abc'], $array['payload']);
    }
}
