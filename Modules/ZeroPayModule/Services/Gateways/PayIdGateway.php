<?php

namespace Modules\ZeroPayModule\Services\Gateways;

use Modules\ZeroPayModule\Contracts\GatewayContract;
use Modules\ZeroPayModule\Models\ZeroPaySession;
use Modules\ZeroPayModule\Models\ZeroPayTransaction;
use Modules\ZeroPayModule\Services\QrCodeService;
use Modules\ZeroPayModule\ValueObjects\GatewayResponse;
use Modules\ZeroPayModule\ValueObjects\WebhookResult;

class PayIdGateway implements GatewayContract
{
    public function __construct(
        protected QrCodeService $qrCodeService,
    ) {}

    /**
     * Create a PayID payment: generate a QR payload, persist the zeropay_qr_codes record,
     * and return a redirect URL pointing to the payment page.
     */
    public function createPayment(array $session): array
    {
        $payId = config('zeropay-module.payid', 'payments@merchant.com');

        /** @var ZeroPaySession|null $sessionModel */
        $sessionModel = ZeroPaySession::query()->find($session['id'] ?? null);

        if ($sessionModel) {
            $merchantName = config('zeropay-module.merchant_name', 'ZeroPay Merchant');

            $qrCode = $this->qrCodeService->generateForSession($sessionModel, $payId, $merchantName);

            $reference = $qrCode->reference;
        } else {
            $reference = $session['session_token'] ?? uniqid('payid_', true);
        }

        return (new GatewayResponse(
            status: 'pending',
            gateway: 'payid',
            reference: $reference,
            data: [
                'pay_id' => $payId,
                'redirect_url' => url('/pay/session/'.$reference),
            ],
        ))->toArray();
    }

    /**
     * Verify a PayID payment by checking zeropay_transactions for a completed record
     * matching the given reference.
     */
    public function verifyPayment(string $reference): array
    {
        $transaction = ZeroPayTransaction::query()
            ->where('gateway_reference', $reference)
            ->where('gateway', 'payid')
            ->where('status', 'completed')
            ->first();

        $status = $transaction ? 'completed' : 'pending';

        return (new GatewayResponse(
            status: $status,
            gateway: 'payid',
            reference: $reference,
        ))->toArray();
    }

    /**
     * Handle an NPP/PayID confirmation webhook.
     */
    public function handleWebhook(array $payload): array
    {
        $reference = $payload['reference'] ?? $payload['payid_reference'] ?? null;

        if ($reference) {
            ZeroPayTransaction::query()
                ->where('gateway_reference', $reference)
                ->where('gateway', 'payid')
                ->where('status', 'pending')
                ->update(['status' => 'completed']);
        }

        return (new WebhookResult(
            processed: true,
            gateway: 'payid',
            payload: $payload,
        ))->toArray();
    }

    public function calculateFee(float $amount): float
    {
        return 0.0;
    }

    public function refundPayment(string $transactionId): array
    {
        return ['status' => 'refunded', 'transaction_id' => $transactionId, 'gateway' => 'payid'];
    }
}
