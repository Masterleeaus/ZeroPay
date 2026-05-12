<?php

namespace Modules\ZeroPayModule\Adapters;

use Modules\ZeroPayModule\Contracts\GatewayContract;

class DefaultGatewayAdapter implements GatewayContract
{
    public function createPayment(array $session): array
    {
        return [
            'status' => 'pending',
            'gateway' => 'default',
            'reference' => uniqid('zpay_', true),
            'session' => $session,
        ];
    }

    public function verifyPayment(string $reference): array
    {
        return [
            'status' => 'pending',
            'reference' => $reference,
            'gateway' => 'default',
        ];
    }

    public function handleWebhook(array $payload): array
    {
        return [
            'processed' => true,
            'gateway' => 'default',
            'payload' => $payload,
        ];
    }

    public function calculateFee(float $amount): float
    {
        return 0.0;
    }

    public function refundPayment(string $transactionId): array
    {
        return [
            'status' => 'refunded',
            'transaction_id' => $transactionId,
            'gateway' => 'default',
        ];
    }
}
