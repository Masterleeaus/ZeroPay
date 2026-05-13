<?php

namespace Modules\ZeroPayModule\Adapters;

use Illuminate\Contracts\Bus\Dispatcher;
use Modules\ZeroPayModule\Contracts\GatewayContract;
use Modules\ZeroPayModule\Jobs\ProcessBankDepositJob;

class BankTransferGatewayAdapter implements GatewayContract
{
    public function createPayment(array $session): array
    {
        return [
            'status' => 'pending',
            'gateway' => 'bank_transfer',
            'reference' => uniqid('bt_', true),
        ];
    }

    public function verifyPayment(string $reference): array
    {
        return ['status' => 'pending', 'reference' => $reference, 'gateway' => 'bank_transfer'];
    }

    public function handleWebhook(array $payload): array
    {
        app(Dispatcher::class)->dispatch(new ProcessBankDepositJob($payload));

        return ['processed' => true, 'queued' => true, 'gateway' => 'bank_transfer'];
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
