<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HospitalsResource\Pages;
use App\Filament\Resources\HospitalsResource\RelationManagers;
use App\Models\Hospitals;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Forms;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class HospitalsResource extends Resource
{
    protected static ?string $model           = Hospitals::class;
    protected static ?string $navigationGroup = 'Master Data';
    protected static ?string $navigationIcon  = 'heroicon-o-building-office-2';

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
            'index'  => Pages\ListHospitals::route('/'),
            'create' => Pages\CreateHospitals::route('/create'),
            'edit'   => Pages\EditHospitals::route('/{record}/edit'),
        ];
    }
}
