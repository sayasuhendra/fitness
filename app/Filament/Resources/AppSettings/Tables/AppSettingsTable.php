<?php

namespace App\Filament\Resources\AppSettings\Tables;

use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AppSettingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('key')
                    ->label('Setting')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'membership_expiry_reminder_days' => 'Reminder membership hampir habis',
                        default => $state,
                    }),
                TextColumn::make('value')
                    ->label('Nilai')
                    ->formatStateUsing(fn ($state): string => is_array($state) ? implode(', ', $state) : (string) $state),
            ])
            ->recordActions([
                EditAction::make(),
            ]);
    }
}
