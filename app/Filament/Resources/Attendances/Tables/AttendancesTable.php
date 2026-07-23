<?php

namespace App\Filament\Resources\Attendances\Tables;

use App\Support\AdminShift;
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
                    ->label('Lokasi')
                    ->searchable(),
                TextColumn::make('handler.name')
                    ->label('Admin')
                    ->placeholder('-')
                    ->toggleable(),
                TextColumn::make('handled_shift')
                    ->label('Shift')
                    ->formatStateUsing(fn (?string $state): string => AdminShift::label($state))
                    ->placeholder('-')
                    ->badge()
                    ->toggleable(),
                TextColumn::make('status')
                    ->label('Status')
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
            ->poll('10s')
            ->defaultSort('check_in_time', 'desc');
    }
}
