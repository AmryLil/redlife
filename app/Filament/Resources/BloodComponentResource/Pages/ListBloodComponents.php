<?php

namespace App\Filament\Resources\BloodComponentResource\Pages;

use App\Filament\Resources\BloodComponentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBloodComponents extends ListRecords
{
    protected static string $resource = BloodComponentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
