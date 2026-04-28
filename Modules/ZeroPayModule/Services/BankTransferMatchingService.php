<?php

namespace Modules\ZeroPayModule\Services;

use Modules\ZeroPayModule\Models\ZeroPayBankDeposit;
use Modules\ZeroPayModule\Models\ZeroPayTransaction;

class BankTransferMatchingService
{
    public function match(ZeroPayBankDeposit $deposit): array
    {
        // 1. Reference match (highest priority)
        if ($deposit->reference) {
            $transaction = ZeroPayTransaction::query()
                ->where('company_id', $deposit->company_id)
                ->where('gateway_reference', $deposit->reference)
                ->first();

            if ($transaction) {
                return $this->applyMatch($deposit, $transaction, 100, 'reference');
            }
        }

        // 2. Amount + approximate timestamp match
        // Only attempt this strategy when we have a timestamp for reliable windowing
        if ($deposit->deposited_at) {
            $transactions = ZeroPayTransaction::query()
                ->where('company_id', $deposit->company_id)
                ->where('amount', $deposit->amount)
                ->where('status', 'pending')
                ->whereBetween('created_at', [
                    $deposit->deposited_at->copy()->subHours(48),
                    $deposit->deposited_at->copy()->addHours(48),
                ])
                ->get();

            if ($transactions->count() === 1) {
                return $this->applyMatch($deposit, $transactions->first(), 80, 'amount_timestamp');
            }
        }

        // No match — flag for manual review
        $deposit->update(['status' => 'pending_review', 'match_score' => 0]);

        return ['matched' => false, 'score' => 0, 'method' => null];
    }

    protected function applyMatch(
        ZeroPayBankDeposit $deposit,
        ZeroPayTransaction $transaction,
        int $score,
        string $method
    ): array {
        $deposit->update([
            'transaction_id' => $transaction->id,
            'status'         => 'matched',
            'match_score'    => $score,
            'match_method'   => $method,
        ]);

        $transaction->update(['status' => 'completed']);

        return [
            'matched'        => true,
            'score'          => $score,
            'method'         => $method,
            'transaction_id' => $transaction->id,
        ];
    }
}
