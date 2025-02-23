<?php

namespace App\Filament\Resources\DonationStatusResource\Pages;

use App\Filament\Resources\DonationStatusResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDonationStatus extends EditRecord
{
    protected static string $resource = DonationStatusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
