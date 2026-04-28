<?php

namespace Modules\ZeroPayModule\Filament\Resources;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Modules\ZeroPayModule\Filament\Resources\ZeroPayBankDepositResource\Pages;
use Modules\ZeroPayModule\Models\ZeroPayBankDeposit;

class ZeroPayBankDepositResource extends Resource
{
    protected static ?string $model = ZeroPayBankDeposit::class;
    protected static ?string $navigationIcon = 'heroicon-o-building-library';
    protected static ?string $navigationGroup = 'ZeroPay';
    protected static ?string $navigationLabel = 'Bank Deposits';
    protected static ?int $navigationSort = 3;

    public static function canViewAny(): bool
    {
        return auth()->user()?->can('zeropay.view') ?? false;
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('amount')->numeric()->required(),
            TextInput::make('currency')->default('AUD')->maxLength(3),
            TextInput::make('depositor_name')->label('Depositor Name'),
            TextInput::make('reference')->label('Reference'),
            TextInput::make('description')->label('Description'),
            Select::make('status')->options([
                'pending_review' => 'Pending Review',
                'matched'        => 'Matched',
                'unmatched'      => 'Unmatched',
                'ignored'        => 'Ignored',
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('amount')->label('Amount')->money('AUD'),
            TextColumn::make('reference')->label('Reference')->searchable(),
            TextColumn::make('depositor_name')->label('Depositor'),
            TextColumn::make('status')->label('Status')->badge()
                ->colors([
                    'warning' => 'pending_review',
                    'success' => 'matched',
                    'danger'  => 'unmatched',
                    'gray'    => 'ignored',
                ]),
            TextColumn::make('match_score')->label('Score'),
            TextColumn::make('created_at')->label('Created')->dateTime()->sortable(),
        ])->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListZeroPayBankDeposits::route('/'),
        ];
    }
}
