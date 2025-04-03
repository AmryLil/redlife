<?php

namespace App\Filament\App\Pages;

use App\Models\BloodComponent;
use App\Models\BloodStock;
use App\Models\BloodStockDetail;
use App\Models\BloodType;
use App\Models\StorageLocations;
use Filament\Forms\Components\Select;
use Filament\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;

class BloodSupply extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string $view         = 'filament.app.pages.blood-supply';
    protected static ?int $navigationSort = 1;
    public $storageLocationId;
    public $selectedCity;

    #[Computed]
    public function cities()
    {
        return StorageLocations::distinct('city')
            ->orderBy('city')
            ->pluck('city');
    }

    #[Computed]
    public function getStorageLocationsProperty()  // Ubah nama method
    {
        return StorageLocations::when($this->selectedCity, function ($query) {
            $query->where('city', $this->selectedCity);
        })
            ->orderBy('name')
            ->get();
    }

    #[Computed]
    public function getSelectedMapUrlProperty()
    {
        // Jika ada lokasi spesifik dipilih
        if ($this->storageLocationId) {
            $location = StorageLocations::find($this->storageLocationId);
            return $location?->url_address;
        }

        // Jika hanya kota yang dipilih (ambil lokasi pertama di kota tersebut)
        if ($this->selectedCity) {
            $location = StorageLocations::where('city', $this->selectedCity)
                ->whereNotNull('url_address')
                ->first();
            return $location?->url_address;
        }

        return null;
    }

    public function clearFilters()
    {
        $this->selectedCity      = null;
        $this->storageLocationId = null;
        $this->resetPage();  // Reset pagination
    }

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
                $query = BloodStock::query()
                    ->when($this->selectedCity, function ($query) {
                        $query->whereHas('storageLocation', function ($q) {
                            $q->where('city', $this->selectedCity);
                        });
                    })
                    ->when($this->storageLocationId, function ($query) {
                        $query->where('storage_location_id', $this->storageLocationId);
                    })
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
        return $record->blood_type_id;  // Pastikan nilai tidak null
    }
}
