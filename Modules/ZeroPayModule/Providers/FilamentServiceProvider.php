<?php

namespace Modules\ZeroPayModule\Providers;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Filament\PanelProvider;
use Modules\ZeroPayModule\ZeroPayModulePlugin;

class FilamentServiceProvider extends PanelProvider
{
    /**
     * Configure the Filament panel and register the ZeroPayModulePlugin.
     */
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->plugin(ZeroPayModulePlugin::make())
            ->resources($this->resources())
            ->pages($this->pages())
            ->widgets($this->widgets());
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
