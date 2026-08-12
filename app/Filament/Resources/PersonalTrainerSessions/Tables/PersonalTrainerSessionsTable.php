<?php

namespace App\Filament\Resources\PersonalTrainerSessions\Tables;

use App\Actions\Attendance\CheckInMemberAction;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PersonalTrainerSessionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('member.user.name')
                    ->label('Member')
                    ->searchable(),
                TextColumn::make('trainer.user.name')
                    ->label('Instruktur Lama')
                    ->placeholder('-')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                TextColumn::make('personalTrainer.user.name')
                    ->label('Personal Trainer')
                    ->placeholder('-')
                    ->searchable(),
                TextColumn::make('scheduled_at')
                    ->label('Jadwal')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('duration_minutes')
                    ->label('Durasi')
                    ->suffix(' menit'),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'scheduled' => 'success',
                        'completed' => 'gray',
                        'cancelled' => 'danger',
                        default => 'warning',
                    }),
                TextColumn::make('access_type')
                    ->label('Akses')
                    ->badge(),
                TextColumn::make('amount')
                    ->money('IDR')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'pending_payment' => 'Menunggu Pembayaran',
                        'scheduled' => 'Terjadwal',
                        'completed' => 'Selesai',
                        'cancelled' => 'Dibatalkan',
                    ]),
                SelectFilter::make('personal_trainer_id')
                    ->label('Personal Trainer')
                    ->relationship('personalTrainer.user', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->recordActions([
                Action::make('check_in_once')
                    ->label('Check-In 1 Sesi')
                    ->icon('heroicon-o-qr-code')
                    ->color('info')
                    ->visible(fn ($record): bool => $record->status === 'scheduled')
                    ->action(function ($record): void {
                        app(CheckInMemberAction::class)->execute(
                            member: $record->member,
                            attendanceType: 'personal_trainer_session',
                            personalTrainerSession: $record,
                            location: 'Personal Trainer',
                            admin: auth()->user(),
                            sessionUnits: 1,
                        );
                    }),
                Action::make('check_in_double')
                    ->label('Check-In 2 Sesi')
                    ->icon('heroicon-o-qr-code')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->modalHeading('Check-in 2 sesi personal trainer?')
                    ->modalDescription('Kuota membership akan dikurangi 2 sesi jika paket member memiliki batas kunjungan.')
                    ->visible(fn ($record): bool => $record->status === 'scheduled')
                    ->action(function ($record): void {
                        app(CheckInMemberAction::class)->execute(
                            member: $record->member,
                            attendanceType: 'personal_trainer_session',
                            personalTrainerSession: $record,
                            location: 'Personal Trainer',
                            admin: auth()->user(),
                            sessionUnits: 2,
                        );
                    }),
                Action::make('complete')
                    ->label('Tandai Selesai')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn ($record): bool => $record->status === 'scheduled')
                    ->action(fn ($record) => $record->update([
                        'status' => 'completed',
                        'completed_at' => now(),
                    ])),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('scheduled_at', 'desc');
    }
}
