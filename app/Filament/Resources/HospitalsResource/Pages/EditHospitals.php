<?php

namespace App\Filament\Resources\HospitalsResource\Pages;

use App\Filament\Resources\HospitalsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditHospitals extends EditRecord
{
    protected static string $resource = HospitalsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
