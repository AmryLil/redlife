<?php

namespace App\Filament\Resources\RequestTypesResource\Pages;

use App\Filament\Resources\RequestTypesResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRequestTypes extends EditRecord
{
    protected static string $resource = RequestTypesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
