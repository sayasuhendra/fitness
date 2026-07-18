<?php

namespace App\Filament\Resources\Attendances\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class AttendancesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('member.user.name')
                    ->label('Member')
                    ->searchable(),
                TextColumn::make('attendance_type')
                    ->label('Jenis')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'class_attendance' => 'Kelas',
                        'personal_trainer_session' => 'Personal Trainer',
                        default => 'Gym Visit',
                    }),
                TextColumn::make('fitnessClass.name')
                    ->label('Kelas')
                    ->placeholder('-')
                    ->searchable(),
                TextColumn::make('personalTrainerSession.trainer.user.name')
                    ->label('Trainer PT')
                    ->placeholder('-')
                    ->searchable(),
                TextColumn::make('check_in_time')
                    ->label('Check-In')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('location')
                    ->searchable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => $state === 'present' ? 'success' : 'danger'),
            ])
            ->filters([
                SelectFilter::make('attendance_type')
                    ->label('Jenis')
                    ->options([
                        'gym_visit' => 'Gym Visit',
                        'class_attendance' => 'Kelas',
                        'personal_trainer_session' => 'Personal Trainer',
                    ]),
                SelectFilter::make('status')
                    ->options([
                        'present' => 'Hadir',
                        'cancelled' => 'Dibatalkan',
                    ]),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('check_in_time', 'desc');
    }
}
