<?php

namespace Modules\ZeroPayModule\Adapters;

use Modules\ZeroPayModule\Contracts\GatewayContract;

class BankTransferGatewayAdapter implements GatewayContract
{
    public function createPayment(array $session): array
    {
        return [
            'status'    => 'pending',
            'gateway'   => 'bank_transfer',
            'reference' => uniqid('bt_', true),
        ];
    }

    public function verifyPayment(string $reference): array
    {
        return ['status' => 'pending', 'reference' => $reference, 'gateway' => 'bank_transfer'];
    }

    public function handleWebhook(array $payload): array
    {
        return ['processed' => true, 'gateway' => 'bank_transfer', 'payload' => $payload];
    }

    public function calculateFee(float $amount): float
    {
        return 0.0;
    }

    public function refundPayment(string $transactionId): array
    {
        return ['status' => 'refunded', 'transaction_id' => $transactionId, 'gateway' => 'bank_transfer'];
    }
}
