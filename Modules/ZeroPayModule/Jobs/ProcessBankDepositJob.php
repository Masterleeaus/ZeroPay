<?php

namespace Modules\ZeroPayModule\Jobs;

use Modules\ZeroPayModule\Services\BankTransferMatchingService;

class ProcessBankDepositJob
{
    public function __construct(public readonly array $rawDepositData) {}

    public function handle(BankTransferMatchingService $matchingService): void
    {
        $deposit = $matchingService->processIncomingDeposit($this->rawDepositData);

        if (in_array((string) $deposit->status, ['matched', 'rejected'], true)) {
            return;
        }

        $matchingService->match($deposit);
    }
}
