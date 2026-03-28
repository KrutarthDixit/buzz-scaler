<?php

namespace App\Filament\Resources\Customers\RelationManagers;

use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class WalletRelationManager extends RelationManager
{
    protected static string $relationship = 'wallet';

    protected static ?string $title = 'Wallet';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('balance')
                    ->label('Balance')
                    ->numeric()
                    ->prefix('$')
                    ->default(0.00)
                    ->required(),

                TextInput::make('transaction_description')
                    ->label('Transaction Description')
                    ->placeholder('Reason for balance change')
                    ->helperText('This will be logged in the wallet transaction history.'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->paginated(false)
            ->columns([
                TextColumn::make('balance')
                    ->label('Current Balance')
                    ->money('USD')
                    ->sortable()
                    ->size('lg')
                    ->weight('bold'),
            ])
            ->headerActions([
                // Removed CreateAction since the wallet is automatically created by the UserObserver
            ])
            ->recordActions([
                Action::make('add_balance')
                    ->label('Add Balance')
                    ->icon('heroicon-o-plus-circle')
                    ->color('success')
                    ->form([
                        TextInput::make('amount')
                            ->label('Amount')
                            ->numeric()
                            ->required()
                            ->minValue(0.01)
                            ->prefix('$'),
                        TextInput::make('description')
                            ->label('Description')
                            ->required()
                            ->default('Added balance via admin'),
                    ])
                    ->action(function ($record, array $data): void {
                        $record->transaction_description = $data['description'];
                        $record->balance += $data['amount'];
                        $record->save();
                    }),

                Action::make('deduct_balance')
                    ->label('Deduct Balance')
                    ->icon('heroicon-o-minus-circle')
                    ->color('danger')
                    ->form([
                        TextInput::make('amount')
                            ->label('Amount')
                            ->numeric()
                            ->required()
                            ->minValue(0.01)
                            ->maxValue(fn($record) => $record->balance)
                            ->prefix('$'),
                        TextInput::make('description')
                            ->label('Description')
                            ->required()
                            ->default('Deducted balance via admin'),
                    ])
                    ->action(function ($record, array $data): void {
                        $record->transaction_description = $data['description'];
                        $record->balance -= $data['amount'];
                        $record->save();
                    }),

                Action::make('refund_balance')
                    ->label('Refund')
                    ->icon('heroicon-o-arrow-path')
                    ->color('warning')
                    ->form([
                        TextInput::make('amount')
                            ->label('Amount')
                            ->numeric()
                            ->required()
                            ->minValue(0.01)
                            ->prefix('$'),
                        TextInput::make('description')
                            ->label('Description')
                            ->required()
                            ->default('Refund to wallet'),
                    ])
                    ->action(function ($record, array $data): void {
                        $record->transaction_description = $data['description'];
                        $record->balance += $data['amount'];
                        $record->save();
                    }),
            ]);
    }
}
