<?php

namespace Modules\ZeroPayModule;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Modules\ZeroPayModule\Filament\Pages\ZeroPayDashboardPage;
use Modules\ZeroPayModule\Filament\Resources\ZeroPayBankDepositResource;
use Modules\ZeroPayModule\Filament\Resources\ZeroPaySessionResource;
use Modules\ZeroPayModule\Filament\Resources\ZeroPayTransactionResource;

class ZeroPayModulePlugin implements Plugin
{
    public static function make(): static
    {
        return app(static::class);
    }

    public function getId(): string
    {
        return 'zeropay-module';
    }

    public function register(Panel $panel): void
    {
        $panel->resources([
            ZeroPaySessionResource::class,
            ZeroPayTransactionResource::class,
            ZeroPayBankDepositResource::class,
        ]);

        $panel->pages([
            ZeroPayDashboardPage::class,
        ]);
    }

    public function boot(Panel $panel): void
    {
        //
    }
}
