<?php

namespace App\Filament\Widgets;

use App\Models\BloodStock as ModelsBloodStock;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class BloodStock extends BaseWidget
{
    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                ModelsBloodStock::query()
            )
            ->columns([
                TextColumn::make('bloodType.full_type')
                    ->label('Blood Type')
                    ->formatStateUsing(fn($record) => $record->bloodType->group . $record->bloodType->rhesus),
                TextColumn::make('total_quantity')
                    ->label('Total Quantity')
                    ->numeric()
                    ->sortable(),
            ])
            ->paginated([3, 5, 8], 'simple');
    }

    // Fix 1: Tambahkan method untuk menentukan key
    protected function getTableRecordKeyUsing(): string
    {
        return '';
    }

    // Fix 2: Override method untuk handle grouped data
}
