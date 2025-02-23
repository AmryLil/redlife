<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DonationStatusResource\Pages;
use App\Filament\Resources\DonationStatusResource\RelationManagers;
use App\Models\DonationStatus;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Forms;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DonationStatusResource extends Resource
{
    protected static ?string $model           = DonationStatus::class;
    protected static ?string $navigationGroup = 'Master Data';
    protected static ?string $navigationIcon  = 'heroicon-o-chart-bar-square';

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
            'index'  => Pages\ListDonationStatuses::route('/'),
            'create' => Pages\CreateDonationStatus::route('/create'),
            'edit'   => Pages\EditDonationStatus::route('/{record}/edit'),
        ];
    }
}
