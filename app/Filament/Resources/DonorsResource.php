<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DonorsResource\Pages;
use App\Filament\Resources\DonorsResource\RelationManagers;
use App\Models\Donations;
use App\Models\Donor;
use App\Models\Donors;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Forms;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DonorsResource extends Resource
{
    protected static ?string $model           = Donations::class;
    protected static ?string $navigationIcon  = 'heroicon-o-circle-stack';
    protected static ?string $navigationLabel = 'Donations';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('user_id')
                    ->label('User ID')
                    ->required(),
                Forms\Components\DatePicker::make('donation_date')
                    ->label('Donation Date')
                    ->required(),
                Forms\Components\TimePicker::make('time')
                    ->label('Donation Time')
                    ->required(),
                Forms\Components\Select::make('location_id')
                    ->label('Location')
                    ->relationship('location', 'location_name')
                    ->required(),
                Forms\Components\Select::make('status_id')
                    ->label('Status')
                    ->options([
                        1 => 'Pending',
                        2 => 'Completed',
                    ])
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('Donation ID')
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Donor Name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('donation_date')
                    ->label('Date')
                    ->date(),
                Tables\Columns\TextColumn::make('time')
                    ->label('Time'),
                Tables\Columns\TextColumn::make('location.location_name')
                    ->label('Location'),
                Tables\Columns\BadgeColumn::make('status_id')
                    ->label('Status')
                    ->colors([
                        'warning' => fn($state) => $state == 1,  // Pending
                        'success' => fn($state) => $state == 2,  // Completed
                    ])
                    ->formatStateUsing(fn($state) => $state == 1 ? 'Pending' : 'Completed'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status_id')
                    ->label('Status')
                    ->options([
                        1 => 'Pending',
                        2 => 'Completed',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            'index'  => Pages\ListDonors::route('/'),
            'create' => Pages\CreateDonors::route('/create'),
            'view'   => Pages\ViewDonors::route('/{record}'),
            'edit'   => Pages\EditDonors::route('/{record}/edit'),
        ];
    }
}
