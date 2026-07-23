<?php

namespace App\Filament\Resources\Orders\Tables;

use App\Actions\Orders\CancelOrderAction;
use App\Filament\Resources\Orders\OrderResource;
use App\Support\AdminShift;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class OrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('member.user.name')
                    ->label('Member')
                    ->searchable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending_payment' => 'Menunggu Pembayaran',
                        'paid' => 'Sudah Dibayar',
                        'completed' => 'Selesai',
                        'cancelled' => 'Dibatalkan',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'paid', 'completed' => 'success',
                        'cancelled' => 'danger',
                        default => 'warning',
                    })
                    ->searchable(),
                TextColumn::make('payment_method')
                    ->label('Pembayaran')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'qris' => 'QRIS',
                        'bank_transfer' => 'Transfer Bank',
                        'cash' => 'Tunai',
                        'manual_transfer' => 'Transfer Manual',
                        default => $state,
                    })
                    ->searchable(),
                TextColumn::make('total_price')
                    ->label('Total')
                    ->money('IDR')
                    ->sortable(),
                TextColumn::make('items_sum_profit_amount')
                    ->label('Profit')
                    ->sum('items', 'profit_amount')
                    ->money('IDR')
                    ->visible(fn (): bool => auth()->user()?->hasAnyRole(['Owner', 'Super admin']) ?? false)
                    ->sortable(),
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
                TextColumn::make('delivered_at')
                    ->label('Diserahkan')
                    ->formatStateUsing(fn ($state): string => $state ? 'Sudah' : 'Belum')
                    ->badge()
                    ->color(fn ($state): string => $state ? 'success' : 'warning'),
                TextColumn::make('payment_reference')
                    ->label('Kode Pembayaran')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Diubah')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'pending_payment' => 'Menunggu Pembayaran',
                        'paid' => 'Sudah Dibayar',
                        'completed' => 'Selesai',
                        'cancelled' => 'Dibatalkan',
                    ]),
                SelectFilter::make('payment_method')
                    ->options([
                        'qris' => 'QRIS',
                        'bank_transfer' => 'Transfer Bank',
                        'cash' => 'Tunai',
                    ]),
            ])
            ->recordActions([
                Action::make('view')
                    ->label('Detail')
                    ->icon('heroicon-o-eye')
                    ->url(fn ($record): string => OrderResource::getUrl('view', ['record' => $record])),
                Action::make('complete')
                    ->label('Selesai')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn ($record): bool => $record->status === 'paid')
                    ->requiresConfirmation()
                    ->action(function ($record): void {
                        $record->update(['status' => 'completed']);

                        Notification::make()
                            ->title('Pesanan ditandai selesai')
                            ->success()
                            ->send();
                    }),
                Action::make('deliver')
                    ->label('Serahkan')
                    ->icon('heroicon-o-hand-thumb-up')
                    ->color('success')
                    ->visible(fn ($record): bool => $record->delivered_at === null && in_array($record->status, ['paid', 'completed'], true))
                    ->requiresConfirmation()
                    ->modalHeading('Pesanan sudah diserahkan ke member?')
                    ->action(function ($record): void {
                        $record->update([
                            'delivered_at' => now(),
                            'delivered_by' => auth()->id(),
                        ]);

                        Notification::make()
                            ->title('Pesanan ditandai sudah diserahkan')
                            ->success()
                            ->send();
                    }),
                Action::make('cancel')
                    ->label('Batalkan')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn ($record): bool => in_array($record->status, ['pending_payment', 'paid'], true))
                    ->requiresConfirmation()
                    ->modalHeading('Batalkan pesanan ini?')
                    ->modalDescription('Stok produk pada pesanan ini akan dikembalikan.')
                    ->action(function ($record): void {
                        app(CancelOrderAction::class)->execute($record);

                        Notification::make()
                            ->title('Pesanan dibatalkan dan stok dikembalikan')
                            ->success()
                            ->send();
                    }),
                EditAction::make()
                    ->label('Edit'),
            ])
            ->recordUrl(fn ($record): string => OrderResource::getUrl('view', ['record' => $record]))
            ->poll('10s')
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
