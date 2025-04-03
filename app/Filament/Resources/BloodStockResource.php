<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BloodStockResource\Pages;
use App\Filament\Widgets\BloodStock as WidgetsBloodStock;
use App\Models\BloodComponent;
use App\Models\BloodStock;
use App\Models\BloodStockDetail;
use App\Models\BloodTypes;
use App\Models\User;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard;
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
    protected static ?string $navigationIcon  = 'healthicons-f-blood-o-p';
    protected static ?string $navigationLabel = 'Blood Stock';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make([
                    Wizard\Step::make('Donor Information')
                        ->schema([
                            Section::make('Donor Information')
                                ->schema([
                                    TextInput::make('name')
                                        ->label('Donor Name')
                                        ->default(fn() => User::find(request('name'))?->name)
                                        ->disabled()
                                        ->dehydrated()
                                        ->visible(fn($operation) => $operation === 'create'),
                                    TextInput::make('donation_id')
                                        ->default(request('donation_id'))
                                        ->visible(fn($operation) => $operation === 'create')
                                        ->label('Donation ID'),
                                    DatePicker::make('collection_date')
                                        ->default(now())
                                        ->disabled()
                                        ->dehydrated(),
                                    Select::make('blood_type_id')
                                        ->label('Blood Type')
                                        ->options(BloodTypes::all()->mapWithKeys(fn($bt) => [
                                            $bt->id => $bt->full_type
                                        ]))
                                        ->required()
                                        ->reactive()
                                        ->searchable(),
                                    Forms\Components\Select::make('storage_location_id')
                                        ->relationship('storageLocation', 'name')
                                        ->label('Storage Location')
                                        ->required(),
                                    TextInput::make('quantity')
                                        ->numeric()
                                        ->required()
                                        ->minValue(1),
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
                                    DatePicker::make('expiry_date')
                                        ->disabled()
                                        ->dehydrated(),
                                    Select::make('status')
                                        ->options([
                                            'available' => 'Available',
                                            'reserved'  => 'Reserved',
                                            'used'      => 'Used',
                                            'expired'   => 'Expired'
                                        ])
                                        ->default('available')
                                        ->required()
                                ])
                                ->columns(2)
                        ])
                        ->columnSpanFull(),
                    // BloodStockResource.php
                    Wizard\Step::make('Komponen Darah')
                        ->schema([
                            Section::make('Komponen Darah')
                                ->schema([
                                    Forms\Components\Repeater::make('bloodComponentStocks')  // Menyimpan ke BloodComponentStock
                                        ->relationship('bloodComponentStocks')  // Sesuai dengan relasi di BloodStock
                                        ->schema([
                                            Select::make('blood_component_id')  // Pilih komponen darah
                                                ->label('Komponen Darah')
                                                ->options(BloodComponent::all()->pluck('name', 'id'))
                                                ->required(),
                                            TextInput::make('quantity')  // Jumlah darah yang disimpan
                                                ->label('Jumlah (ml)')
                                                ->numeric()
                                                ->required()
                                                ->minValue(1),
                                        ])
                                        ->columns(2)
                                        ->minItems(1)  // Harus ada minimal 1 komponen darah yang dipilih
                                ])
                        ])
                ])
                    ->key('blood_stock_wizard')
                    ->submitAction(
                        Action::make('submit')
                            ->label('Simpan Data')
                            ->submit('submit')
                            ->icon('heroicon-m-check')
                    )
                    ->nextAction(
                        fn() => Action::make('next')
                            ->label('Next')
                            ->color('primary')
                            ->action(fn($livewire) => $livewire->dispatch('wizard::nextStep'))
                    )
                    ->previousAction(
                        fn() => Action::make('previous')
                            ->label('Back')
                            ->color('gray')
                            ->submit('previous')
                    )
                    ->persistStepInQueryString()
            ])
            ->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->heading('Blood Stock Details')
            ->columns([
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

    public static function getWidgets(): array
    {
        return [
            \App\Filament\Widgets\BloodStock::class
        ];
    }
}
