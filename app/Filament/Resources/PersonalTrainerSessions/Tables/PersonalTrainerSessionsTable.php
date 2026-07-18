<?php

namespace App\Filament\Resources\PersonalTrainerSessions\Tables;

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
                    ->label('Trainer')
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
                SelectFilter::make('trainer_id')
                    ->label('Trainer')
                    ->relationship('trainer.user', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->recordActions([
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
