<?php

namespace Modules\ZeroPayModule\Adapters;

use Modules\ZeroPayModule\Contracts\GatewayContract;

class CashGatewayAdapter implements GatewayContract
{
    public function createPayment(array $session): array
    {
        return [
            'status' => 'pending',
            'gateway' => 'cash',
            'reference' => uniqid('cash_', true),
        ];
    }

    public function verifyPayment(string $reference): array
    {
        return ['status' => 'pending', 'reference' => $reference, 'gateway' => 'cash'];
    }

    public function handleWebhook(array $payload): array
    {
        return ['processed' => true, 'gateway' => 'cash', 'payload' => $payload];
    }

    public function calculateFee(float $amount): float
    {
        return 0.0;
    }

    public function refundPayment(string $transactionId): array
    {
        return ['status' => 'refunded', 'transaction_id' => $transactionId, 'gateway' => 'cash'];
    }
}
