<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BloodRequestResource\Pages;
use App\Filament\Resources\BloodRequestResource\RelationManagers;
use App\Models\BloodRequest;
use App\Models\BloodRequestType;
use App\Models\BloodTypes;
use App\Models\DonationLocation;
use App\Models\Hospitals;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Table;
use Filament\Forms;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BloodRequestResource extends Resource
{
    protected static ?string $model           = BloodRequest::class;
    protected static ?string $navigationGroup = 'Blood Management';
    protected static ?string $navigationIcon  = 'fluentui-branch-request-20';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Permintaan Darah')
                    ->columns(2)
                    ->schema([
                        Forms\Components\Select::make('hospital_id')
                            ->label('Hospital')
                            ->options(Hospitals::all()->pluck('name', 'id'))
                            ->searchable()
                            ->required(),
                        Select::make('blood_type_id')
                            ->label('Blood Type')
                            ->options(BloodTypes::all()->mapWithKeys(fn($bt) => [
                                $bt->id => $bt->full_type
                            ]))
                            ->required()
                            ->reactive()
                            ->searchable(),
                        Forms\Components\Select::make('status_id')
                            ->label('Status Permintaan')
                            ->options(BloodRequestType::all()->pluck('name', 'id'))
                            ->default(1)
                            ->required(),
                        Forms\Components\TextInput::make('quantity')
                            ->label('Jumlah (kantong)')
                            ->numeric()
                            ->minValue(1)
                            ->required(),
                        Forms\Components\Textarea::make('desc')
                            ->label('Deskripsi Kebutuhan')
                            ->columnSpanFull()
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('hospital.name')
                    ->label('Hospital')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('bloodType')->label('Blood Type')->formatStateUsing(function ($record) {
                    return optional($record->bloodType)->group . optional($record->bloodType)->rhesus;
                }),
                Tables\Columns\TextColumn::make('quantity')
                    ->label('Qty'),
                Tables\Columns\TextColumn::make('requestStatus.name')
                    ->label('Status'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('urgency_level')
                    ->options([
                        'low'       => 'normal',
                        'medium'    => 'critical',
                        'high'      => 'urgent',
                        'emergency' => 'critical'
                    ]),
                Tables\Filters\SelectFilter::make('status_id')
                    ->relationship('requestStatus', 'name')
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
