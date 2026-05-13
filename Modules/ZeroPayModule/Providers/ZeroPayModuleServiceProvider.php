<?php

namespace Modules\ZeroPayModule\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\ZeroPayModule\Adapters\DefaultGatewayAdapter;
use Modules\ZeroPayModule\Contracts\GatewayContract;
use Modules\ZeroPayModule\Services\BankTransferMatchingService;
use Modules\ZeroPayModule\Services\GatewayFactory;
use Modules\ZeroPayModule\Services\GatewayRegistry;
use Modules\ZeroPayModule\Services\Gateways\BankTransferGateway;
use Modules\ZeroPayModule\Services\Gateways\PayIdGateway;
use Modules\ZeroPayModule\Services\PaymentSessionService;
use Modules\ZeroPayModule\Services\QrCodeService;
use Modules\ZeroPayModule\Services\WebPushService;

class ZeroPayModuleServiceProvider extends ServiceProvider
{
    protected string $moduleName = 'ZeroPayModule';

    protected string $moduleNameLower = 'zeropay-module';

    /**
     * Register services.
     */
    public function register(): void
    {
        $this->registerConfig();
        $this->app->bind(GatewayContract::class, DefaultGatewayAdapter::class);
        $this->app->singleton(GatewayFactory::class);
        $this->app->singleton(GatewayRegistry::class);
        $this->app->singleton(BankTransferMatchingService::class);
        $this->app->singleton(PaymentSessionService::class);
        $this->app->singleton(QrCodeService::class);
        $this->app->singleton(WebPushService::class);
        $this->app->singleton(PayIdGateway::class);
        $this->app->singleton(BankTransferGateway::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->registerMigrations();
        $this->registerViews();
        $this->registerTranslations();
        $this->registerCommands();
    }

    /**
     * Register migrations from Database/Migrations/.
     */
    protected function registerMigrations(): void
    {
        $this->loadMigrationsFrom(module_path($this->moduleName, 'Database/Migrations'));
    }

    /**
     * Register config from Config/module.php.
     */
    protected function registerConfig(): void
    {
        $this->mergeConfigFrom(
            module_path($this->moduleName, 'Config/module.php'),
            $this->moduleNameLower
        );

        $this->publishes([
            module_path($this->moduleName, 'Config/module.php') => config_path($this->moduleNameLower.'.php'),
        ], 'config');
    }

    /**
     * Register views from Resources/views/.
     */
    protected function registerViews(): void
    {
        $this->loadViewsFrom(
            module_path($this->moduleName, 'Resources/views'),
            $this->moduleNameLower
        );

        $this->publishes([
            module_path($this->moduleName, 'Resources/views') => resource_path('views/modules/'.$this->moduleNameLower),
        ], 'views');
    }

    /**
     * Register translations from Resources/lang/.
     */
    protected function registerTranslations(): void
    {
        $this->loadTranslationsFrom(
            module_path($this->moduleName, 'Resources/lang'),
            $this->moduleNameLower
        );

        $this->publishes([
            module_path($this->moduleName, 'Resources/lang') => lang_path('modules/'.$this->moduleNameLower),
        ], 'translations');
    }

    /**
     * Register console commands from Console/Commands/.
     */
    protected function registerCommands(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands(
                $this->discoverCommands()
            );
        }
    }

    /**
     * Discover command classes in Console/Commands directory.
     *
     * @return array<int, class-string>
     */
    protected function discoverCommands(): array
    {
        $commandPath = module_path($this->moduleName, 'Console/Commands');

        if (! is_dir($commandPath)) {
            return [];
        }

        $commands = [];
        foreach (glob($commandPath.'/*.php') as $file) {
            $class = 'Modules\\'.$this->moduleName.'\\Console\\Commands\\'.pathinfo($file, PATHINFO_FILENAME);
            if (class_exists($class)) {
                $commands[] = $class;
            }
        }

        return $commands;
    }
}
