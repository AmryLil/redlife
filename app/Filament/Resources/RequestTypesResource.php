<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RequestTypesResource\Pages;
use App\Filament\Resources\RequestTypesResource\RelationManagers;
use App\Models\BloodRequestType;
use App\Models\RequestTypes;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Forms;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RequestTypesResource extends Resource
{
    protected static ?string $model           = BloodRequestType::class;
    protected static ?string $navigationGroup = 'Master Data';
    protected static ?string $navigationIcon  = 'heroicon-o-rectangle-stack';

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
            'index'  => Pages\ListRequestTypes::route('/'),
            'create' => Pages\CreateRequestTypes::route('/create'),
            'edit'   => Pages\EditRequestTypes::route('/{record}/edit'),
        ];
    }
}
