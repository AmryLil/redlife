<?php

namespace App\Filament\Resources\DonorsResource\Pages;

use App\Filament\Resources\DonorsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDonors extends EditRecord
{
    protected static string $resource = DonorsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
