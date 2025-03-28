<?php

namespace App\Filament\Resources\BloodStockResource\Pages;

use App\Filament\Resources\BloodStockResource;
use App\Filament\Widgets\BloodStock;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions;

class ListBloodStocks extends ListRecords
{
    protected static string $resource = BloodStockResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            BloodStock::class,
        ];
    }
}
