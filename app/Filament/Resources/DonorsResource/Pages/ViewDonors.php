<?php

namespace App\Filament\Resources\DonorsResource\Pages;

use App\Filament\Resources\DonorsResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewDonors extends ViewRecord
{
    protected static string $resource = DonorsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
