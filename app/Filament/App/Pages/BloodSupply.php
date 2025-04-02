<?php

namespace App\Filament\App\Pages;

use Doctrine\DBAL\Schema\Table;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Pages\Page;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;

class BloodSupply extends Page
{
    protected static string $view         = 'filament.app.pages.blood-supply';
    protected static ?int $navigationSort = 1;

    public static function table(Table $table): Table
    {
        return $table
            ->heading('Blood Stock Details')
            ->columns([
                TextColumn::make('donations.user.name')->label('Name')->sortable(),
                TextColumn::make('bloodType')->label('Blood Type')->formatStateUsing(function ($record) {
                    return optional($record->bloodType)->group . optional($record->bloodType)->rhesus;
                }),
                TextColumn::make('storageLocation.name')->label('Storage Location')->sortable(),
                TextColumn::make('quantity')->label('Quantity')->sortable(),
                TextColumn::make('expiry_date')->label('Expiry Date')->date()->sortable(),
                BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'success' => 'available',
                        'warning' => 'reserved',
                        'danger'  => 'expired',
                    ]),
            ])
            ->filters([
                SelectFilter::make('blood_component')
                    ->label('Blood Component')
                    ->options([
                        'whole_blood'     => 'Whole Blood',
                        'plasma'          => 'Plasma',
                        'platelets'       => 'Platelets',
                        'red_blood_cells' => 'Red Blood Cells',
                    ]),
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'available' => 'Available',
                        'reserved'  => 'Reserved',
                        'used'      => 'Used',
                        'expired'   => 'Expired',
                    ]),
            ])
            ->actions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
            ]);
    }

    public static function canView(): bool
    {
        return true;
    }
}
