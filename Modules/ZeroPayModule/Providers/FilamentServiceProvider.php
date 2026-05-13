<?php

namespace Modules\ZeroPayModule\Providers;

use Filament\Facades\Filament;
use Illuminate\Support\ServiceProvider;
use Modules\ZeroPayModule\Filament\Pages\ZeroPayControlPanel;
use Modules\ZeroPayModule\Filament\Pages\ZeroPayDashboardPage;
use Modules\ZeroPayModule\Filament\Resources\ZeroPayBankDepositResource;
use Modules\ZeroPayModule\Filament\Resources\ZeroPaySessionResource;
use Modules\ZeroPayModule\Filament\Resources\ZeroPayTransactionResource;
use Modules\ZeroPayModule\ZeroPayModulePlugin;

class FilamentServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * Registers ZeroPayModulePlugin with every Filament panel that is
     * discovered, and declares the module's Filament resources, pages,
     * and widgets.
     */
    public function boot(): void
    {
        Filament::serving(function () {
            Filament::registerPlugin(ZeroPayModulePlugin::make());

            Filament::registerResources($this->resources());
            Filament::registerPages($this->pages());
            Filament::registerWidgets($this->widgets());
        });
    }

    /**
     * Filament resources provided by this module.
     *
     * @return array<int, class-string>
     */
    protected function resources(): array
    {
        return [
            ZeroPaySessionResource::class,
            ZeroPayTransactionResource::class,
            ZeroPayBankDepositResource::class,
        ];
    }

    protected function pages(): array
    {
        return [
            ZeroPayDashboardPage::class,
            ZeroPayControlPanel::class,
        ];
    }

    protected function widgets(): array
    {
        return [];
    }
}
