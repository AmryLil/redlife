<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BloodStockResource\Pages;
use App\Models\BloodStock;
use App\Models\BloodTypes;
use App\Models\User;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Forms;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BloodStockResource extends Resource
{
    protected static ?string $model           = BloodStock::class;
    protected static ?string $navigationIcon  = 'heroicon-o-server-stack';
    protected static ?string $navigationLabel = 'Blood Stock';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Donor Name')
                    ->default(function ($get) {
                        $userId = request('name');
                        if (!$userId)
                            return null;
                        return User::find($userId)?->name;
                    })
                    ->disabled()
                    ->visible(fn($operation) => $operation === 'create')
                    ->dehydrated(),
                TextInput::make('donation_id')
                    ->default(request('donation_id'))
                    ->visible(fn($operation) => $operation === 'create')
                    ->label('Donation ID'),
                DatePicker::make('collection_date')
                    ->default(request('date'))
                    ->visible(fn($operation) => $operation === 'create')
                    ->label('Collection Date')
                    ->disabled(),
                Select::make('blood_type_id')
                    ->label('Blood Types')
                    ->options(fn() => BloodTypes::all()->mapWithKeys(fn($bloodType) => [
                        $bloodType->id => "{$bloodType->group}{$bloodType->rhesus}"
                    ]))
                    ->searchable(),
                Forms\Components\Select::make('storage_location_id')
                    ->relationship('storageLocation', 'name')
                    ->label('Storage Location')
                    ->required(),
                Forms\Components\TextInput::make('quantity')
                    ->numeric()
                    ->label('Quantity')
                    ->required(),
                Forms\Components\TextInput::make('expiry_days')
                    ->label('Expiry in Days')
                    ->numeric()
                    ->required()
                    ->live()
                    ->afterStateUpdated(function (Get $get, callable $set) {
                        $collectionDate = $get('collection_date');
                        $expiryDays     = $get('expiry_days');
                        if ($collectionDate && $expiryDays) {
                            $expiryDate = date('Y-m-d', strtotime("+$expiryDays days", strtotime($collectionDate)));
                            $set('expiry_date', $expiryDate);
                        }
                    }),
                Forms\Components\DatePicker::make('expiry_date')
                    ->label('Expiry Date')
                    ->disabled()  // Tidak bisa diedit oleh user
                    ->dehydrated(),
                Forms\Components\Select::make('blood_component')
                    ->label('Blood Component')
                    ->options([
                        'whole_blood'     => 'Whole Blood',
                        'plasma'          => 'Plasma',
                        'platelets'       => 'Platelets',
                        'red_blood_cells' => 'Red Blood Cells',
                    ])
                    ->required(),
                Forms\Components\Select::make('status')
                    ->label('Status')
                    ->options([
                        'available' => 'Available',
                        'reserved'  => 'Reserved',
                        'used'      => 'Used',
                        'expired'   => 'Expired',
                    ])
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->schema([
                Section::make('Informasi Utama')
                    ->columns([
                        Tables\Columns\TextColumn::make('id')->label('ID')->sortable(),
                        Tables\Columns\TextColumn::make('donations.user.name')->label('Name')->sortable(),
                        Tables\Columns\TextColumn::make('bloodType')->label('Blood Type')->formatStateUsing(function ($record) {
                            return optional($record->bloodType)->group . optional($record->bloodType)->rhesus;
                        }),
                        Tables\Columns\TextColumn::make('storageLocation.name')->label('Storage Location')->sortable(),
                        Tables\Columns\TextColumn::make('quantity')->label('Quantity')->sortable(),
                        Tables\Columns\TextColumn::make('expiry_date')->label('Expiry Date')->date()->sortable(),
                        Tables\Columns\BadgeColumn::make('status')
                            ->label('Status')
                            ->colors([
                                'success' => 'available',
                                'warning' => 'reserved',
                                'danger'  => 'expired',
                            ]),
                    ]),
                Section::make('Informasi Utama')
                    ->columns([
                        Tables\Columns\TextColumn::make('id')->label('ID')->sortable(),
                        Tables\Columns\TextColumn::make('donations.user.name')->label('Name')->sortable(),
                        Tables\Columns\TextColumn::make('bloodType')->label('Blood Type')->formatStateUsing(function ($record) {
                            return optional($record->bloodType)->group . optional($record->bloodType)->rhesus;
                        }),
                        Tables\Columns\TextColumn::make('storageLocation.name')->label('Storage Location')->sortable(),
                        Tables\Columns\TextColumn::make('quantity')->label('Quantity')->sortable(),
                        Tables\Columns\TextColumn::make('expiry_date')->label('Expiry Date')->date()->sortable(),
                        Tables\Columns\BadgeColumn::make('status')
                            ->label('Status')
                            ->colors([
                                'success' => 'available',
                                'warning' => 'reserved',
                                'danger'  => 'expired',
                            ]),
                    ])
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('blood_component')
                    ->label('Blood Component')
                    ->options([
                        'whole_blood'     => 'Whole Blood',
                        'plasma'          => 'Plasma',
                        'platelets'       => 'Platelets',
                        'red_blood_cells' => 'Red Blood Cells',
                    ]),
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'available' => 'Available',
                        'reserved'  => 'Reserved',
                        'used'      => 'Used',
                        'expired'   => 'Expired',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListBloodStocks::route('/'),
            'create' => Pages\CreateBloodStock::route('/create'),
            'edit'   => Pages\EditBloodStock::route('/{record}/edit'),
        ];
    }
}
