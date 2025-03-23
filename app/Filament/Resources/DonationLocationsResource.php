<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DonationLocationsResource\Pages;
use App\Filament\Resources\DonationLocationsResource\RelationManagers;
use App\Models\DonationLocation;
use App\Models\DonationLocations;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Forms;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DonationLocationsResource extends Resource
{
    protected static ?string $model           = DonationLocation::class;
    protected static ?string $navigationGroup = 'Master Data';
    protected static ?string $navigationIcon  = 'heroicon-o-map-pin';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(1)
                    ->schema([
                        TextInput::make('location_name')
                            ->label('Location Name')
                            ->required(),
                        TextInput::make('city')
                            ->label('District/City')
                            ->required(),
                        TextInput::make('address')
                            ->label('Address')
                            ->required(),
                        TextInput::make('location_detail')
                            ->label('Location Detail')
                            ->required(),
                        TextInput::make('url_address')
                            ->label('URL Address')
                            ->required(),
                        FileUpload::make('cover')
                            ->label('Cover')
                            ->image()
                            ->directory('covers')
                            ->required()
                            ->imageEditor()
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('location_name')
                    ->label('Location Name'),
                TextColumn::make('city')
                    ->label('District/City'),
                TextColumn::make('address')
                    ->wrap(),
                TextColumn::make('location_detail')
                    ->label('Location Detail'),
                ImageColumn::make('cover')
                    ->disk('public')
                    ->url(fn($record) => asset('storage/' . $record->cover))
                    ->label('Cover')
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListDonationLocations::route('/'),
            'create' => Pages\CreateDonationLocations::route('/create'),
            'edit'   => Pages\EditDonationLocations::route('/{record}/edit'),
        ];
    }
}
