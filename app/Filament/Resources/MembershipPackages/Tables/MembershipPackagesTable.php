<?php

namespace App\Filament\Resources\MembershipPackages\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class MembershipPackagesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('package_type')
                    ->label('Type')
                    ->badge(),
                TextColumn::make('billing_cycle')
                    ->label('Billing')
                    ->badge(),
                IconColumn::make('includes_personal_trainer')
                    ->label('PT')
                    ->boolean(),
                TextColumn::make('visit_limit')
                    ->label('Visits')
                    ->formatStateUsing(fn ($state, $record): string => $record->has_visit_limit ? "{$state} visits" : 'Unlimited')
                    ->badge(),
                TextColumn::make('duration_days')
                    ->label('Days')
                    ->numeric(),
                TextColumn::make('discount_percent')
                    ->label('Discount')
                    ->suffix('%')
                    ->numeric(),
                TextColumn::make('price')
                    ->money('IDR')
                    ->sortable(),
                IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),
            ])
            ->filters([
                SelectFilter::make('package_type')
                    ->options([
                        'membership' => 'Membership',
                        'one_time' => 'One-time Visitor',
                    ]),
                SelectFilter::make('billing_cycle')
                    ->options([
                        'monthly' => 'Monthly',
                        'yearly' => 'Yearly',
                        'one_time' => 'One Time',
                    ]),
                TernaryFilter::make('includes_personal_trainer')
                    ->label('Includes Personal Trainer'),
                TernaryFilter::make('is_active')
                    ->label('Available'),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
