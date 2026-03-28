<?php

namespace App\Filament\Resources\Orders\Schemas;

use App\Models\Service;
use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->label('Customer')
                    ->options(
                        User::role('customer')->pluck('name', 'id')
                    )
                    ->searchable()
                    ->preload()
                    ->required(),

                Select::make('service_id')
                    ->label('Service')
                    ->relationship('service', 'name')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->reactive(),

                TextInput::make('url')
                    ->label('URL')
                    ->url()
                    ->nullable()
                    ->placeholder('https://example.com/post'),

                TextInput::make('quantity')
                    ->label('Quantity')
                    ->numeric()
                    ->required()
                    ->default(1)
                    ->minValue(1)
                    ->rules([
                        function (\Filament\Forms\Get $get) {
                            return function (string $attribute, $value, \Closure $fail) use ($get) {
                                $serviceId = $get('service_id');
                                if (!$serviceId) return;

                                $service = Service::find($serviceId);
                                if (!$service) return;

                                if ($value < $service->min) {
                                    $fail("The minimum quantity for this service is {$service->min}.");
                                }
                                if ($value > $service->max) {
                                    $fail("The maximum quantity for this service is {$service->max}.");
                                }
                            };
                        },
                    ]),
            ]);
    }
}
