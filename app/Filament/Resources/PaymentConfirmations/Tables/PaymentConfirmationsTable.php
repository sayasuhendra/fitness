<?php

namespace App\Filament\Resources\PaymentConfirmations\Tables;

use App\Actions\Payments\ApprovePaymentConfirmationAction;
use App\Actions\Payments\RejectPaymentConfirmationAction;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PaymentConfirmationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('member.user.name')
                    ->label('Member')
                    ->searchable(),
                TextColumn::make('payable_type')
                    ->label('Transaksi')
                    ->formatStateUsing(fn (string $state, $record): string => class_basename($state).' #'.$record->payable_id)
                    ->searchable(),
                TextColumn::make('payment_method')
                    ->label('Metode')
                    ->badge(),
                TextColumn::make('amount')
                    ->label('Nominal')
                    ->money('IDR')
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'approved' => 'success',
                        'rejected' => 'danger',
                        default => 'warning',
                    }),
                TextColumn::make('created_at')
                    ->label('Dikirim')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('verified_at')
                    ->label('Diverifikasi')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'pending' => 'Menunggu Konfirmasi',
                        'approved' => 'Diterima',
                        'rejected' => 'Ditolak',
                    ]),
                SelectFilter::make('payment_method')
                    ->options([
                        'qris' => 'QRIS',
                        'bank_transfer' => 'Transfer Bank',
                        'cash' => 'Tunai',
                    ]),
            ])
            ->recordActions([
                Action::make('approve')
                    ->label('Terima')
                    ->color('success')
                    ->icon('heroicon-o-check-circle')
                    ->visible(fn ($record): bool => $record->status === 'pending')
                    ->schema([
                        Textarea::make('admin_note')
                            ->label('Catatan Admin')
                            ->rows(3),
                    ])
                    ->action(function ($record, array $data): void {
                        app(ApprovePaymentConfirmationAction::class)->execute($record, auth()->user(), $data['admin_note'] ?? null);

                        Notification::make()
                            ->title('Pembayaran diterima')
                            ->success()
                            ->send();
                    }),
                Action::make('reject')
                    ->label('Tolak')
                    ->color('danger')
                    ->icon('heroicon-o-x-circle')
                    ->visible(fn ($record): bool => $record->status === 'pending')
                    ->schema([
                        Textarea::make('admin_note')
                            ->label('Alasan Penolakan')
                            ->rows(3)
                            ->required(),
                    ])
                    ->action(function ($record, array $data): void {
                        app(RejectPaymentConfirmationAction::class)->execute($record, auth()->user(), $data['admin_note'] ?? null);

                        Notification::make()
                            ->title('Pembayaran ditolak')
                            ->danger()
                            ->send();
                    }),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
