<?php

namespace Modules\ZeroPayModule;

use Filament\Contracts\Plugin;
use Filament\Panel;

class ZeroPayModulePlugin implements Plugin
{
    /**
     * Create a new plugin instance.
     */
    public static function make(): static
    {
        return app(static::class);
    }

    /**
     * Return the unique plugin identifier.
     */
    public function getId(): string
    {
        return 'zeropay-module';
    }

    /**
     * Register any plugin services.
     */
    public function register(Panel $panel): void
    {
        //
    }

    /**
     * Bootstrap any plugin services.
     */
    public function boot(Panel $panel): void
    {
        //
    }
}
