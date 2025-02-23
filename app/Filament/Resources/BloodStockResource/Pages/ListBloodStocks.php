<?php

namespace App\Filament\Resources\BloodStockResource\Pages;

use App\Filament\Resources\BloodStockResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBloodStocks extends ListRecords
{
    protected static string $resource = BloodStockResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
