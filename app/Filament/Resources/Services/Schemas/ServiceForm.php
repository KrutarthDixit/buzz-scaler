<?php

namespace App\Filament\Resources\Services\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Schema;

class ServiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->columnSpanFull()
                    ->disabled(),
                TextInput::make('service_id')
                    ->required()
                    ->numeric()
                    ->prefix('#')
                    ->disabled(),
                TextInput::make('type')
                    ->default(null)
                    ->disabled(),
                TextInput::make('rate')
                    ->default(null)
                    ->prefix('$')
                    ->disabled(),
                TextInput::make('min')
                    ->numeric()
                    ->default(null)
                    ->disabled(),
                TextInput::make('max')
                    ->numeric()
                    ->default(null)
                    ->disabled(),
                TextInput::make('category')
                    ->default(null)
                    ->disabled(),
                Group::make()
                    ->columns(3)
                    ->columnSpanFull()
                    ->Schema([
                        Toggle::make('dripfeed')
                            ->required()
                            ->disabled(),
                        Toggle::make('refill')
                            ->required()
                            ->disabled(),
                        Toggle::make('cancel')
                            ->required()
                            ->disabled(),
                    ]),

                TextInput::make('commission_percentage')
                    ->numeric()
                    ->suffix('%')
                    ->reactive()
                    ->afterStateUpdated(function ($state, \Filament\Schemas\Components\Utilities\Set $set, \Filament\Schemas\Components\Utilities\Get $get) {
                        $rate = $get('rate');

                        if ($rate !== null && $state !== null) {
                            $finalAmount = $rate * (1 + ($state / 100));
                            $set('final_amount', round($finalAmount, 5));
                        }
                    }),

                TextInput::make('final_amount')
                    ->numeric()
                    ->prefix('$')
                    ->default(null)
                    ->disabled()
                    ->reactive(),
            ]);
    }
}
