<?php

namespace App\Filament\Resources\DonorsResource\Pages;

use App\Filament\Resources\DonorsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDonors extends ListRecords
{
    protected static string $resource = DonorsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
