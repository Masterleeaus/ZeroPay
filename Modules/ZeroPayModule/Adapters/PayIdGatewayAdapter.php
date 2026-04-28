<?php

namespace Modules\ZeroPayModule\Adapters;

use Modules\ZeroPayModule\Contracts\GatewayContract;

class PayIdGatewayAdapter implements GatewayContract
{
    public function createPayment(array $session): array
    {
        return [
            'status'    => 'pending',
            'gateway'   => 'payid',
            'reference' => uniqid('payid_', true),
            'pay_id'    => config('zeropay-module.payid', 'payments@merchant.com'),
        ];
    }

    public function verifyPayment(string $reference): array
    {
        return ['status' => 'pending', 'reference' => $reference, 'gateway' => 'payid'];
    }

    public function handleWebhook(array $payload): array
    {
        return ['processed' => true, 'gateway' => 'payid', 'payload' => $payload];
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
