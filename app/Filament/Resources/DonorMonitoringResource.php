<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DonorMonitoringResource\Pages;
use App\Filament\Resources\DonorMonitoringResource\RelationManagers;
use App\Models\Donations;
use App\Models\DonationStatus;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Forms;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;

class DonorMonitoringResource extends Resource
{
    protected static ?string $model           = Donations::class;
    protected static ?string $navigationLabel = 'Monitoring Donor';
    protected static ?string $navigationGroup = 'Donor Management';
    protected static ?string $navigationIcon  = 'carbon-cloud-monitoring';

    // Tambahkan status rejected
    protected static array $statuses = [
        1 => 'Pending',
        2 => 'Completed',
        3 => 'Rejected',  // Tambahkan status rejected
    ];

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
                    ->options(DonationStatus::all()->pluck('status', 'id'))
                    ->required()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn(Builder $query) => $query->whereNotIn('status_id', [3, 7, 8]))
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('Donation ID')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Donor Name')
                    ->searchable()
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
                        'warning' => 1,  // Pending
                        'gray'    => 2,  // Completed
                        'danger'  => 3,  // Rejected
                        'info'    => 4,  // Completed
                        'success' => 5,  // Completed
                    ])
                    ->formatStateUsing(function ($state) {
                        return \App\Models\DonationStatus::find($state)?->status ?? 'Unknown';
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status_id')
                    ->label('Status')
                    ->options(static::$statuses),
            ])
            ->actions([
                Tables\Actions\Action::make('updateStatus')
                    ->label('Ubah Status')
                    ->icon('heroicon-o-pencil')
                    ->modalHeading('Update Donation Status')
                    ->form([
                        Forms\Components\Select::make('status_id')
                            ->label('Status')
                            ->options(DonationStatus::all()->pluck('status', 'id'))
                            ->required()
                    ])
                    ->action(function (Donations $record, array $data) {
                        $record->status_id = $data['status_id'];
                        $record->save();

                        // Tambahkan redirect jika status completed
                        if ($data['status_id'] == 8) {  // ID 2 = Completed
                            return redirect()->to(
                                BloodStockResource::getUrl('create', [
                                    'donation_id' => $record->id,
                                    'name'        => $record->user_id,
                                    'date'        => $record->donation_date,
                                ])
                            );
                        }
                    }),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListDonorMonitorings::route('/'),
            'create' => Pages\CreateDonorMonitoring::route('/create'),
            'edit'   => Pages\EditDonorMonitoring::route('/{record}/edit'),
        ];
    }
}
