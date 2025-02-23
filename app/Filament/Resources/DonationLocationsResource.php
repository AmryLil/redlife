<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DonationLocationsResource\Pages;
use App\Filament\Resources\DonationLocationsResource\RelationManagers;
use App\Models\DonationLocation;
use App\Models\DonationLocations;
use Filament\Forms\Form;
use Filament\Resources\Resource;
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
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
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
