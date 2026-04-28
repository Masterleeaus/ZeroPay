<?php

namespace Modules\ZeroPayModule\Filament\Resources\ZeroPayBankDepositResource\Pages;

use Filament\Resources\Pages\ListRecords;
use Modules\ZeroPayModule\Filament\Resources\ZeroPayBankDepositResource;

class ListZeroPayBankDeposits extends ListRecords
{
    protected static string $resource = ZeroPayBankDepositResource::class;
}
