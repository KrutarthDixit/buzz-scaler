<?php

namespace App\Filament\Resources\Customers\Pages;

use App\Filament\Resources\Customers\CustomerResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Contracts\Support\Htmlable;

class CreateCustomer extends CreateRecord
{
    protected static string $resource = CustomerResource::class;

    public function getTitle(): string|Htmlable
    {
        if (filled(static::$title)) {
            return static::$title;
        }

        return __('Create Customer');
    }


    protected function afterCreate(): void
    {
        /** @var \App\Models\User $user */
        $user = $this->getRecord();
        $user->assignRole('customer');
    }
}
