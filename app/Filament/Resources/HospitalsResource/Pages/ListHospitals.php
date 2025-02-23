<?php

namespace App\Filament\Resources\HospitalsResource\Pages;

use App\Filament\Resources\HospitalsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListHospitals extends ListRecords
{
    protected static string $resource = HospitalsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
