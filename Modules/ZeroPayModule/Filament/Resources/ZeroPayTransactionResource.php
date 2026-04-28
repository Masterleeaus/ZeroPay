<?php

namespace Modules\ZeroPayModule\Filament\Resources;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Modules\ZeroPayModule\Filament\Resources\ZeroPayTransactionResource\Pages;
use Modules\ZeroPayModule\Models\ZeroPayTransaction;

class ZeroPayTransactionResource extends Resource
{
    protected static ?string $model = ZeroPayTransaction::class;
    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    protected static ?string $navigationGroup = 'ZeroPay';
    protected static ?string $navigationLabel = 'Transactions';
    protected static ?int $navigationSort = 2;

    public static function canViewAny(): bool
    {
        return auth()->user()?->can('zeropay.view') ?? false;
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('gateway_reference')->label('Reference'),
            TextInput::make('gateway')->label('Gateway'),
            TextInput::make('amount')->numeric(),
            TextInput::make('currency')->default('AUD')->maxLength(3),
            Select::make('status')->options([
                'pending'   => 'Pending',
                'completed' => 'Completed',
                'failed'    => 'Failed',
                'refunded'  => 'Refunded',
            ]),
            TextInput::make('fee')->numeric(),
            TextInput::make('net_amount')->numeric(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('gateway_reference')->label('Reference')->searchable(),
            TextColumn::make('gateway')->label('Gateway'),
            TextColumn::make('amount')->label('Amount')->money('AUD'),
            TextColumn::make('fee')->label('Fee')->money('AUD'),
            TextColumn::make('status')->label('Status')->badge(),
            TextColumn::make('created_at')->label('Created')->dateTime()->sortable(),
        ])->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListZeroPayTransactions::route('/'),
        ];
    }
}
