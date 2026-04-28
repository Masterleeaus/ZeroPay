<?php
namespace Modules\ZeroPayModule\Filament\Resources;
use Filament\Resources\Resource;use Modules\ZeroPayModule\Models\ZeroPaySession;
class ZeroPaySessionResource extends Resource{protected static ?string $model=ZeroPaySession::class;protected static ?string $navigationIcon='heroicon-o-squares-2x2';public static function canViewAny():bool{return auth()->user()?->can('zeropay.view')??false;}}
