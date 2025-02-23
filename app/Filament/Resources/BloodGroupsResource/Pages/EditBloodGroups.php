<?php

namespace App\Filament\Resources\BloodGroupsResource\Pages;

use App\Filament\Resources\BloodGroupsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBloodGroups extends EditRecord
{
    protected static string $resource = BloodGroupsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
