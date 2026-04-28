<?php

namespace Modules\ZeroPayModule\Adapters;

use Modules\ZeroPayModule\Contracts\GatewayContract;

class StripeGatewayAdapter implements GatewayContract
{
    public function createPayment(array $session): array
    {
        return [
            'status'    => 'pending',
            'gateway'   => 'stripe',
            'reference' => uniqid('stripe_', true),
        ];
    }

    public function verifyPayment(string $reference): array
    {
        return ['status' => 'pending', 'reference' => $reference, 'gateway' => 'stripe'];
    }

    public function handleWebhook(array $payload): array
    {
        return ['processed' => true, 'gateway' => 'stripe', 'payload' => $payload];
    }

    public function calculateFee(float $amount): float
    {
        return round($amount * 0.029 + 0.30, 2);
    }

    public function refundPayment(string $transactionId): array
    {
        return ['status' => 'refunded', 'transaction_id' => $transactionId, 'gateway' => 'stripe'];
    }
}
