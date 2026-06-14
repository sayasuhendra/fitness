<?php

namespace App\Filament\Resources\FitnessClasses\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class FitnessClassesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('trainer.user.name')
                    ->label('Trainer')
                    ->searchable(),
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('class_type')
                    ->label('Type')
                    ->badge()
                    ->searchable(),
                TextColumn::make('confirmed_bookings_count')
                    ->label('Booked')
                    ->counts(['bookings' => fn ($query) => $query->where('status', 'confirmed')])
                    ->badge()
                    ->color(fn ($state, $record): string => $state >= $record->capacity ? 'danger' : 'success'),
                TextColumn::make('capacity')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('location')
                    ->searchable(),
                TextColumn::make('class_date')
                    ->label('Start Date')
                    ->date()
                    ->sortable(),
                TextColumn::make('recurring_days')
                    ->label('Weekly')
                    ->formatStateUsing(fn ($state, $record): string => $record->is_recurring ? implode(', ', $record->recurring_days ?? []) : 'No')
                    ->badge(),
                TextColumn::make('start_time')
                    ->time()
                    ->sortable(),
                TextColumn::make('end_time')
                    ->time()
                    ->sortable(),
                IconColumn::make('is_active')
                    ->label('Open')
                    ->boolean(),
                IconColumn::make('allow_drop_in')
                    ->label('Drop-in')
                    ->boolean(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('trainer_id')
                    ->label('Trainer')
                    ->relationship('trainer.user', 'name')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('class_type')
                    ->options([
                        'zumba' => 'Zumba',
                        'zumba_gold' => 'Zumba Gold',
                        'yoga' => 'Yoga',
                        'prenatal_yoga' => 'Yoga Prenatal',
                        'circuit_training' => 'Circuit Training',
                        'pilates' => 'Pilates',
                        'strength' => 'Strength',
                        'aerobic' => 'Aerobic',
                        'aeromix' => 'Aeromix',
                        'fitdance' => 'Fitdance',
                        'bomiya' => 'Bomiya',
                        'poundfit' => 'Poundfit',
                        'gym' => 'Gym',
                        'personal_trainer' => 'Personal Trainer',
                        'body_fat' => 'Body Fat',
                        'general' => 'General',
                    ]),
                TernaryFilter::make('is_active')
                    ->label('Open for booking'),
                TernaryFilter::make('is_recurring')
                    ->label('Repeats weekly'),
                TernaryFilter::make('allow_drop_in')
                    ->label('Allows one-time visitor'),
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
