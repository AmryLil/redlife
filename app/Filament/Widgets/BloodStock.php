<?php

namespace App\Filament\Widgets;

use App\Models\BloodComponent;
use App\Models\BloodStock as BloodStockModel;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\DB;

class BloodStock extends BaseWidget
{
    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        $components = BloodComponent::all();

        $columns = [
            TextColumn::make('bloodType.full_type')
                ->label('Blood Type')
                ->formatStateUsing(function ($record) {
                    return $record->bloodType->group . $record->bloodType->rhesus;
                })
                ->sortable(),
            TextColumn::make('total_quantity')
                ->label('Total Stock')
                ->numeric()
                ->sortable()
                ->formatStateUsing(fn($state) => number_format($state, 0, ',', '.'))
                ->color('success')
        ];

        // Tambahkan kolom untuk setiap komponen darah
        foreach ($components as $component) {
            $columns[] = TextColumn::make("component_{$component->id}")
                ->label($component->name)
                ->formatStateUsing(function ($record) use ($component) {
                    $quantity = $record->{"component_{$component->id}"} ?? 0;
                    return number_format($quantity, 0, ',', '.') . ' unit';
                })
                ->alignCenter()
                ->color('warning');
        }

        return $table
            ->query(function () use ($components) {
                $query = BloodStockModel::query()
                    ->select('blood_stocks.blood_type_id', DB::raw('SUM(blood_stocks.quantity) as total_quantity'))
                    ->groupBy('blood_stocks.blood_type_id');

                // Tambahkan subquery untuk menghitung total quantity per komponen darah
                foreach ($components as $component) {
                    $query->selectRaw("
                        COALESCE((
                            SELECT SUM(blood_stock_component.quantity)
                            FROM blood_stock_component
                            WHERE blood_stock_component.blood_component_id = ?
                        ), 0) AS component_{$component->id}
                    ", [$component->id]);
                }

                return $query->with('bloodType');
            })
            ->columns($columns)
            ->paginated([3, 5, 8], 'simple');
    }

    public function getTableRecordKey($record): string
    {
        return $record->blood_type_id;
    }
}
