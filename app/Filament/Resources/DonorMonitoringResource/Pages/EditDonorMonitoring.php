<?php

namespace App\Filament\Resources\DonorMonitoringResource\Pages;

use App\Filament\Resources\BloodStockResource;
use App\Filament\Resources\DonorMonitoringResource;
use Filament\Resources\Pages\EditRecord;
use Filament\Actions;

class EditDonorMonitoring extends EditRecord
{
    protected static string $resource = DonorMonitoringResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
