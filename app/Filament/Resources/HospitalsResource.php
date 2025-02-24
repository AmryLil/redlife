<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HospitalsResource\Pages;
use App\Filament\Resources\HospitalsResource\RelationManagers;
use App\Models\Hospitals;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
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

class HospitalsResource extends Resource
{
    protected static ?string $model           = Hospitals::class;
    protected static ?string $navigationGroup = 'Master Data';
    protected static ?string $navigationIcon  = 'heroicon-o-building-office-2';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(1)
                    ->schema([
                        TextInput::make('name')
                            ->label('Hospital Name')
                            ->required(),
                        TextInput::make('address')
                            ->label('Address')
                            ->required(),
                        TextInput::make('url_address')
                            ->label('URL Address')
                            ->required(),
                        TextInput::make('contact')
                            ->label('Contact')
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
                TextColumn::make('name')
                    ->label('Hospital Name'),
                TextColumn::make('address')
                    ->wrap(),
                TextColumn::make('contact'),
                ImageColumn::make('cover')
                    ->disk('public')
                    ->url(fn($record) => asset('storage/' . $record->cover))
                    ->label('Cover')
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
