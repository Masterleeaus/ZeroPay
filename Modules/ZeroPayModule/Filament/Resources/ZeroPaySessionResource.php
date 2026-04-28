<?php

namespace Modules\ZeroPayModule\Filament\Resources;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Modules\ZeroPayModule\Filament\Resources\ZeroPaySessionResource\Pages;
use Modules\ZeroPayModule\Models\ZeroPaySession;

class ZeroPaySessionResource extends Resource
{
    protected static ?string $model = ZeroPaySession::class;
    protected static ?string $navigationIcon = 'heroicon-o-credit-card';
    protected static ?string $navigationGroup = 'ZeroPay';
    protected static ?string $navigationLabel = 'Payment Sessions';
    protected static ?string $modelLabel = 'Payment Session';
    protected static ?int $navigationSort = 1;

    public static function canViewAny(): bool
    {
        return auth()->user()?->can('zeropay.view') ?? false;
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('session_token')->label('Session Token')->disabled(),
            Select::make('gateway')
                ->label('Gateway')
                ->options([
                    'payid'         => 'PayID',
                    'bank_transfer' => 'Bank Transfer',
                    'cash'          => 'Cash',
                    'stripe'        => 'Stripe',
                    'paypal'        => 'PayPal',
                    'cryptomus'     => 'Cryptomus',
                ])
                ->required(),
            TextInput::make('amount')->label('Amount')->numeric(),
            TextInput::make('currency')->label('Currency')->default('AUD')->maxLength(3),
            Select::make('status')
                ->label('Status')
                ->options([
                    'pending'   => 'Pending',
                    'open'      => 'Open',
                    'active'    => 'Active',
                    'completed' => 'Completed',
                    'expired'   => 'Expired',
                    'cancelled' => 'Cancelled',
                ])
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('session_token')->label('Token')->limit(20)->searchable(),
            TextColumn::make('gateway')->label('Gateway'),
            TextColumn::make('amount')->label('Amount')->money('AUD'),
            TextColumn::make('status')->label('Status')->badge()
                ->colors([
                    'warning' => 'pending',
                    'info'    => 'open',
                    'primary' => 'active',
                    'success' => 'completed',
                    'danger'  => fn ($state) => in_array($state, ['expired', 'cancelled']),
                ]),
            TextColumn::make('created_at')->label('Created')->dateTime()->sortable(),
        ])->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListZeroPaySessions::route('/'),
            'create' => Pages\CreateZeroPaySession::route('/create'),
            'edit'   => Pages\EditZeroPaySession::route('/{record}/edit'),
        ];
    }
}
