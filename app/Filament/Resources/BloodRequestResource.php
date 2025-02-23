<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BloodRequestResource\Pages;
use App\Filament\Resources\BloodRequestResource\RelationManagers;
use App\Models\BloodRequest;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Forms;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BloodRequestResource extends Resource
{
    protected static ?string $model          = BloodRequest::class;
    protected static ?string $navigationIcon = 'heroicon-o-presentation-chart-line';

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
            'index'  => Pages\ListBloodRequests::route('/'),
            'create' => Pages\CreateBloodRequest::route('/create'),
            'edit'   => Pages\EditBloodRequest::route('/{record}/edit'),
        ];
    }
}
