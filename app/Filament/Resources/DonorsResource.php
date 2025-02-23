<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DonorsResource\Pages;
use App\Filament\Resources\DonorsResource\RelationManagers;
use App\Models\Donor;
use App\Models\Donors;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Forms;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DonorsResource extends Resource
{
    protected static ?string $model          = Donor::class;
    protected static ?string $navigationIcon = 'heroicon-o-circle-stack';

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
                Tables\Actions\ViewAction::make(),
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
            'index'  => Pages\ListDonors::route('/'),
            'create' => Pages\CreateDonors::route('/create'),
            'view'   => Pages\ViewDonors::route('/{record}'),
            'edit'   => Pages\EditDonors::route('/{record}/edit'),
        ];
    }
}
