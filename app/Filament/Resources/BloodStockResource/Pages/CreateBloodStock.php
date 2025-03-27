<?php

namespace App\Filament\Resources\BloodStockResource\Pages;

use App\Filament\Resources\BloodStockResource;
use App\Models\Donations;
use Filament\Resources\Pages\CreateRecord;
use Filament\Actions;

class CreateBloodStock extends CreateRecord
{
    protected static string $resource = BloodStockResource::class;
}
