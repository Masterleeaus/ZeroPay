<?php
namespace Modules\ZeroPayModule\Filament;
use Filament\Contracts\Plugin;use Filament\Panel;use Modules\ZeroPayModule\Filament\Resources\ZeroPaySessionResource;
class ZeroPayModulePlugin implements Plugin{public function getId():string{return 'zeropay-module';}public function register(Panel $panel):void{$panel->resources([ZeroPaySessionResource::class]);}public function boot(Panel $panel):void{}public static function make():self{return app(self::class);}}
