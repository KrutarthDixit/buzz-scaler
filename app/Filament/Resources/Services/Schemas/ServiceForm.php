<?php

namespace App\Filament\Resources\Services\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ServiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('service_id')
                    ->required()
                    ->numeric(),
                TextInput::make('name')
                    ->required(),
                TextInput::make('type')
                    ->default(null),
                TextInput::make('rate')
                    ->default(null),
                TextInput::make('min')
                    ->numeric()
                    ->default(null),
                TextInput::make('max')
                    ->numeric()
                    ->default(null),
                Toggle::make('dripfeed')
                    ->required(),
                Toggle::make('refill')
                    ->required(),
                Toggle::make('cancel')
                    ->required(),
                TextInput::make('category')
                    ->default(null),
            ]);
    }
}
