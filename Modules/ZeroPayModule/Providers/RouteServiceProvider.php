<?php
namespace Modules\ZeroPayModule\Providers;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;use Illuminate\Support\Facades\Route;
class RouteServiceProvider extends ServiceProvider{public function boot():void{$this->routes(function(){Route::middleware(['web','auth'])->prefix('zeropay-module')->name('zeropay-module.')->group(__DIR__.'/../Routes/web.php');Route::middleware(['api','auth:sanctum'])->prefix('api/zeropay-module')->name('api.zeropay-module.')->group(__DIR__.'/../Routes/api.php');Route::middleware(['web','auth'])->prefix('admin/zeropay-module')->name('admin.zeropay-module.')->group(__DIR__.'/../Routes/admin.php');});}}
