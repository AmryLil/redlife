<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BloodGroupsResource\Pages;
use App\Filament\Resources\BloodGroupsResource\RelationManagers;
use App\Models\BloodGroups;
use App\Models\BloodTypes;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Forms;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BloodGroupsResource extends Resource
{
    protected static ?string $model           = BloodTypes::class;
    protected static ?string $navigationGroup = 'Master Data';
    protected static ?string $navigationIcon  = 'heroicon-o-view-columns';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(1)
                    ->schema([
                        TextInput::make('group')
                            ->label('Blood Group')
                            ->required(),
                        TextInput::make('rhesus')
                            ->label('Rhesus')
                            ->required(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('group'),
                TextColumn::make('rhesus'),
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
            'index'  => Pages\ListBloodGroups::route('/'),
            'create' => Pages\CreateBloodGroups::route('/create'),
            'edit'   => Pages\EditBloodGroups::route('/{record}/edit'),
        ];
    }
}
