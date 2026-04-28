<?php

namespace Modules\ZeroPayModule\Providers;

use Filament\Facades\Filament;
use Illuminate\Support\ServiceProvider;
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
        return [];
    }

    /**
     * Filament pages provided by this module.
     *
     * @return array<int, class-string>
     */
    protected function pages(): array
    {
        return [];
    }

    /**
     * Filament widgets provided by this module.
     *
     * @return array<int, class-string>
     */
    protected function widgets(): array
    {
        return [];
    }
}
