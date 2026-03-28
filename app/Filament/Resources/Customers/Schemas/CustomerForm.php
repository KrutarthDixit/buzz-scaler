<?php

namespace App\Filament\Resources\Customers\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class CustomerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->disabledOn('edit'),
                TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255)
                    ->disabledOn('edit'),
                TextInput::make('password')
                    ->password()
                    ->required(fn($operation) => $operation === 'create')
                    ->maxLength(255)
                    ->dehydrated(fn($state) => filled($state))
                    ->visible(fn($operation) => $operation === 'create'),
            ]);
    }
}
