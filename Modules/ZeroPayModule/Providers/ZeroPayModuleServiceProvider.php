<?php
namespace Modules\ZeroPayModule\Providers;
use Illuminate\Support\ServiceProvider;
class ZeroPayModuleServiceProvider extends ServiceProvider{public function register():void{$this->mergeConfigFrom(__DIR__.'/../Config/module.php','zeropay-module');} public function boot():void{$this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');$this->loadViewsFrom(__DIR__.'/../Resources/views','zeropay-module');$this->loadTranslationsFrom(__DIR__.'/../Resources/lang','zeropay-module');}}
