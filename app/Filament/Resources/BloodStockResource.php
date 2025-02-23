<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BloodStockResource\Pages;
use App\Filament\Resources\BloodStockResource\RelationManagers;
use App\Models\BloodStock;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Forms;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BloodStockResource extends Resource
{
    protected static ?string $model          = BloodStock::class;
    protected static ?string $navigationIcon = 'heroicon-o-server-stack';

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
            'index'  => Pages\ListBloodStocks::route('/'),
            'create' => Pages\CreateBloodStock::route('/create'),
            'edit'   => Pages\EditBloodStock::route('/{record}/edit'),
        ];
    }
}
