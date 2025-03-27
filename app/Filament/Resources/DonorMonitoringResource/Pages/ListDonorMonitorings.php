<?php

namespace App\Filament\Resources\DonorMonitoringResource\Pages;

use App\Filament\Resources\DonorMonitoringResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDonorMonitorings extends ListRecords
{
    protected static string $resource = DonorMonitoringResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
