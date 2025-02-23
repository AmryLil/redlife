<?php

namespace App\Filament\Resources\BloodGroupsResource\Pages;

use App\Filament\Resources\BloodGroupsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBloodGroups extends ListRecords
{
    protected static string $resource = BloodGroupsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
