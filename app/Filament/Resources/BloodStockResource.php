<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BloodStockResource\Pages;
use App\Filament\Resources\BloodStockResource\RelationManagers;
use App\Models\BloodStock;
use App\Models\BloodTypes;
use App\Models\Hospitals;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
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
                Grid::make(1)
                    ->schema([
                        Select::make('blood_type_id')
                            ->label('Blood Types')
                            ->options(fn() => BloodTypes::all()->mapWithKeys(fn($bloodType) => [
                                $bloodType->id => "{$bloodType->group}{$bloodType->rhesus}"
                            ]))
                            ->searchable(),
                        Select::make('hospital_id')
                            ->label('Hospital')
                            ->options(Hospitals::all()->pluck('name', 'id'))
                            ->searchable(),
                        TextInput::make('quantity')
                            ->label('Quantity')
                            ->required()
                            ->integer()
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('bloodType.group')
                    ->label('Blood Type')
                    ->formatStateUsing(fn($record) => "{$record->bloodType->group}{$record->bloodType->rhesus}")
                    ->sortable()
                    ->searchable(),
                TextColumn::make('hospital.name')
                    ->label('Hospital')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('quantity')
                    ->label('Quantity'),
                TextColumn::make('created_at')
                    ->dateTime(),
                TextColumn::make('updated_at')
                    ->dateTime()
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
