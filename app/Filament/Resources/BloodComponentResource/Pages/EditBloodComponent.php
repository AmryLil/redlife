<?php

namespace App\Filament\Resources\BloodComponentResource\Pages;

use App\Filament\Resources\BloodComponentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBloodComponent extends EditRecord
{
    protected static string $resource = BloodComponentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
