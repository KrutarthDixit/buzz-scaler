<?php

namespace App\Filament\Resources\Services\Pages;

use App\Filament\Resources\Services\ServiceResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Http;
use App\Models\Service;
use Filament\Notifications\Notification;

class ListServices extends ListRecords
{
    protected static string $resource = ServiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('update_services')
                ->label('Update Services List')
                ->action(function () {
                    $response = Http::asForm()->post('https://smoothsmm.com/api/v2', [
                        'key' => '2e6ffaba2f372a072711544b8eda844d',
                        'action' => 'services',
                    ]);

                    if ($response->successful()) {
                        $services = $response->json();
                        
                        if (is_array($services)) {
                            foreach ($services as $serviceData) {
                                if (!isset($serviceData['service'])) continue;
                                
                                Service::updateOrCreate(
                                    ['service_id' => $serviceData['service']],
                                    [
                                        'name' => $serviceData['name'] ?? '',
                                        'type' => $serviceData['type'] ?? '',
                                        'rate' => (string) ($serviceData['rate'] ?? '0'),
                                        'min' => $serviceData['min'] ?? 0,
                                        'max' => $serviceData['max'] ?? 0,
                                        'dripfeed' => $serviceData['dripfeed'] ?? false,
                                        'refill' => $serviceData['refill'] ?? false,
                                        'cancel' => $serviceData['cancel'] ?? false,
                                        'category' => $serviceData['category'] ?? '',
                                    ]
                                );
                            }
                            
                            Notification::make()
                                ->title('Services synced successfully')
                                ->success()
                                ->send();
                        } else {
                            Notification::make()
                                ->title('Invalid response from API')
                                ->danger()
                                ->send();
                        }
                    } else {
                        Notification::make()
                            ->title('API connection failed')
                            ->danger()
                            ->send();
                    }
                })
                ->icon('heroicon-o-arrow-path'),
        ];
    }
}
