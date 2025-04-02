<?php

namespace App\Filament\App\Pages;

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
        return $table
            ->query(function () {
                return BloodStockDetail::query()
                    ->when($this->selectedCity, function ($query) {
                        $query->whereHas('storageLocation', function ($q) {
                            $q->where('city', $this->selectedCity);
                        });
                    })
                    ->when($this->storageLocationId, function ($query) {
                        $query->where('storage_location_id', $this->storageLocationId);
                    })
                    ->selectRaw('
                    blood_type_id,
                    SUM(quantity) as total_quantity,
                    COUNT(*) as total_donasi
                ')
                    ->groupBy('blood_type_id')
                    ->with(['bloodType' => function ($query) {
                        $query->select('id', 'group', 'rhesus');
                    }]);
            })
            ->columns([
                TextColumn::make('bloodType.full_type')
                    ->label('Golongan Darah')
                    ->formatStateUsing(function ($record) {
                        return $record->bloodType->group . $record->bloodType->rhesus;
                    })
                    ->sortable(),
                TextColumn::make('total_quantity')
                    ->label('Total Stok (ml)')
                    ->numeric()
                    ->sortable()
                    ->formatStateUsing(fn($state) => number_format($state, 0, ',', '.'))
                    ->color('success'),
            ])
            ->filters([
                // Filter khusus lokasi penyimpanan di atas
                Filter::make('storage_location_filter')
                    ->form([
                        Select::make('storage_location_id')
                            ->label('Lokasi Penyimpanan')
                            ->options(StorageLocations::all()->pluck('name', 'id'))
                            ->searchable()
                            ->placeholder('Pilih Lokasi Penyimpanan')
                    ])
                    ->query(function (Builder $query, array $data) {  // Hapus type declaration salah
                        if (!empty($data['storage_location_id'])) {
                            $query->where('storage_location_id', $data['storage_location_id']);
                        }
                    })
                    ->columnSpanFull(),
                SelectFilter::make('blood_component')
                    ->label('Komponen Darah')
                    ->options([
                        'whole_blood'     => 'Darah Lengkap',
                        'plasma'          => 'Plasma',
                        'platelets'       => 'Trombosit',
                        'red_blood_cells' => 'Sel Darah Merah'
                    ])
            ]);
    }

    public function getTableRecordKey($record): string
    {
        return $record->blood_type_id;  // Pastikan nilai tidak null
    }
}
