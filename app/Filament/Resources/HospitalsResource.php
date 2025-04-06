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
    protected static ?string $navigationGroup = 'Organization';
    protected static ?string $navigationIcon  = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Hospital Informations')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('contact')
                            ->label('Contact')
                            ->tel()
                            ->required(),
                        Forms\Components\Textarea::make('address')
                            ->label('Address')
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('url_address')
                            ->label('URL')
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\FileUpload::make('cover')
                            ->label('Cover')
                            ->directory('hospitals/covers')
                            ->image()
                            ->imageEditor()
                            ->columnSpanFull()
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('cover')
                    ->label('Cover')
                    ->circular(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('contact')
                    ->label('Contact')
                    ->searchable(),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
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
