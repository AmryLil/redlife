<?php

namespace App\Filament\Resources\DonationLocationsResource\Pages;

use App\Filament\Resources\DonationLocationsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDonationLocations extends ListRecords
{
    protected static string $resource = DonationLocationsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
