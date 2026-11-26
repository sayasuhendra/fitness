<?php

namespace App\Filament\Resources\Orders\Tables;

use App\Actions\Orders\CancelOrderAction;
use App\Filament\Resources\Orders\OrderResource;
use App\Models\Order;
use App\Services\Notifications\MemberNotificationService;
use App\Support\AdminShift;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;

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
                        'manual_transfer' => 'Transfer Manual',
                    ]),
            ])
            ->recordActions([
                Action::make('view')
                    ->label('Detail')
                    ->icon('heroicon-o-eye')
                    ->url(fn ($record): string => OrderResource::getUrl('view', ['record' => $record])),

                Action::make('mark_as_paid')
                    ->label('Tandai Dibayar')
                    ->icon('heroicon-o-banknotes')
                    ->color('success')
                    ->visible(fn ($record): bool => $record->status === 'pending_payment')
                    ->requiresConfirmation()
                    ->modalHeading('Tandai Pesanan Sudah Dibayar?')
                    ->modalDescription('Status pesanan akan diubah menjadi Sudah Dibayar (paid) dan dicatat masuk laporan shift.')
                    ->modalSubmitActionLabel('Ya, Sudah Dibayar')
                    ->action(function (Order $record): void {
                        $updateData = ['status' => 'paid'];
                        if ($record->handled_by === null) {
                            $updateData = array_merge($updateData, AdminShift::stamp(auth()->user()));
                        }
                        $record->update($updateData);

                        if ($record->member?->user) {
                            app(MemberNotificationService::class)->send(
                                $record->member->user,
                                'Pesanan sudah dibayar',
                                'Pesanan produk Anda sudah tercatat lunas di Akhwat Gym.',
                                'order_paid',
                                '/orders/'.$record->id,
                            );
                        }

                        Notification::make()
                            ->title('Pesanan ditandai sudah dibayar')
                            ->success()
                            ->send();
                    }),

                Action::make('update_status')
                    ->label('Ubah Status')
                    ->icon('heroicon-o-credit-card')
                    ->color('warning')
                    ->modalHeading(fn (Order $record): string => "Ubah Status Pesanan #{$record->id}")
                    ->modalDescription('Ubah status pembayaran atau alur pesanan secara langsung.')
                    ->modalSubmitActionLabel('Simpan Perubahan')
                    ->form([
                        Select::make('status')
                            ->label('Status Pesanan')
                            ->options([
                                'pending_payment' => 'Menunggu Pembayaran',
                                'paid' => 'Sudah Dibayar',
                                'completed' => 'Selesai',
                                'cancelled' => 'Dibatalkan',
                            ])
                            ->default(fn (Order $record): string => $record->status)
                            ->required(),
                        Select::make('payment_method')
                            ->label('Metode Pembayaran')
                            ->options([
                                'cash' => 'Tunai',
                                'qris' => 'QRIS',
                                'bank_transfer' => 'Transfer Bank',
                                'manual_transfer' => 'Transfer Manual',
                            ])
                            ->default(fn (Order $record): string => $record->payment_method)
                            ->required(),
                    ])
                    ->action(function (Order $record, array $data): void {
                        $newStatus = $data['status'];
                        $newPaymentMethod = $data['payment_method'];

                        if ($newStatus === 'cancelled' && $record->status !== 'cancelled') {
                            app(CancelOrderAction::class)->execute($record);
                            if ($newPaymentMethod !== $record->payment_method) {
                                $record->update(['payment_method' => $newPaymentMethod]);
                            }

                            Notification::make()
                                ->title('Pesanan dibatalkan dan stok dikembalikan')
                                ->success()
                                ->send();

                            return;
                        }

                        $updateData = [
                            'status' => $newStatus,
                            'payment_method' => $newPaymentMethod,
                        ];

                        if ($newStatus === 'paid' && $record->status !== 'paid' && $record->handled_by === null) {
                            $updateData = array_merge($updateData, AdminShift::stamp(auth()->user()));
                        }

                        $record->update($updateData);

                        if ($newStatus === 'paid' && $record->member?->user) {
                            app(MemberNotificationService::class)->send(
                                $record->member->user,
                                'Pesanan sudah dibayar',
                                'Pesanan produk Anda sudah tercatat lunas di Akhwat Gym.',
                                'order_paid',
                                '/orders/'.$record->id,
                            );
                        }

                        Notification::make()
                            ->title('Status pesanan berhasil diperbarui')
                            ->success()
                            ->send();
                    }),

                Action::make('complete')
                    ->label('Selesai')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn ($record): bool => $record->status === 'paid')
                    ->requiresConfirmation()
                    ->modalHeading('Tandai Pesanan Selesai?')
                    ->modalSubmitActionLabel('Ya, Selesai')
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
                    ->modalDescription('Waktu penyerahan dan admin yang bertugas akan dicatat.')
                    ->modalSubmitActionLabel('Ya, Sudah Diserahkan')
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
                    ->modalDescription('Stok produk pada pesanan ini akan dikembalikan secara otomatis.')
                    ->modalSubmitActionLabel('Ya, Batalkan')
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
                    BulkAction::make('bulk_mark_as_paid')
                        ->label('Tandai Sudah Dibayar')
                        ->icon('heroicon-o-banknotes')
                        ->color('success')
                        ->requiresConfirmation()
                        ->modalHeading('Tandai Pesanan Terpilih Sudah Dibayar?')
                        ->modalDescription('Semua pesanan terpilih yang berstatus Menunggu Pembayaran akan diubah menjadi Sudah Dibayar (paid) dan masuk ke laporan shift.')
                        ->modalSubmitActionLabel('Ya, Tandai Dibayar')
                        ->action(function (Collection $records): void {
                            $count = 0;
                            $stamp = AdminShift::stamp(auth()->user());
                            $notificationService = app(MemberNotificationService::class);

                            foreach ($records as $record) {
                                if ($record->status === 'pending_payment') {
                                    $updateData = ['status' => 'paid'];
                                    if ($record->handled_by === null) {
                                        $updateData = array_merge($updateData, $stamp);
                                    }
                                    $record->update($updateData);

                                    if ($record->member?->user) {
                                        $notificationService->send(
                                            $record->member->user,
                                            'Pesanan sudah dibayar',
                                            'Pesanan produk Anda sudah tercatat lunas di Akhwat Gym.',
                                            'order_paid',
                                            '/orders/'.$record->id,
                                        );
                                    }
                                    $count++;
                                }
                            }

                            Notification::make()
                                ->title("{$count} pesanan ditandai sudah dibayar")
                                ->success()
                                ->send();
                        })
                        ->deselectRecordsAfterCompletion(),

                    BulkAction::make('bulk_complete')
                        ->label('Tandai Selesai')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->modalHeading('Tandai Pesanan Terpilih Selesai?')
                        ->modalDescription('Pesanan terpilih yang berstatus Sudah Dibayar akan ditandai Selesai (completed).')
                        ->modalSubmitActionLabel('Ya, Tandai Selesai')
                        ->action(function (Collection $records): void {
                            $count = 0;
                            foreach ($records as $record) {
                                if ($record->status === 'paid') {
                                    $record->update(['status' => 'completed']);
                                    $count++;
                                }
                            }

                            Notification::make()
                                ->title("{$count} pesanan ditandai selesai")
                                ->success()
                                ->send();
                        })
                        ->deselectRecordsAfterCompletion(),

                    BulkAction::make('bulk_deliver')
                        ->label('Serahkan Produk')
                        ->icon('heroicon-o-hand-thumb-up')
                        ->color('success')
                        ->requiresConfirmation()
                        ->modalHeading('Tandai Produk Terpilih Sudah Diserahkan?')
                        ->modalDescription('Pesanan yang belum diserahkan (dan berstatus sudah dibayar atau selesai) akan dicatat sudah diserahkan ke member.')
                        ->modalSubmitActionLabel('Ya, Sudah Diserahkan')
                        ->action(function (Collection $records): void {
                            $count = 0;
                            $userId = auth()->id();
                            $now = now();

                            foreach ($records as $record) {
                                if ($record->delivered_at === null && in_array($record->status, ['paid', 'completed'], true)) {
                                    $record->update([
                                        'delivered_at' => $now,
                                        'delivered_by' => $userId,
                                    ]);
                                    $count++;
                                }
                            }

                            Notification::make()
                                ->title("{$count} pesanan ditandai sudah diserahkan")
                                ->success()
                                ->send();
                        })
                        ->deselectRecordsAfterCompletion(),

                    BulkAction::make('bulk_cancel')
                        ->label('Batalkan Pesanan')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->modalHeading('Batalkan Pesanan Terpilih?')
                        ->modalDescription('Stok produk pada pesanan yang dibatalkan akan dikembalikan secara otomatis. Pesanan yang sudah selesai tidak akan dibatalkan.')
                        ->modalSubmitActionLabel('Ya, Batalkan Pesanan')
                        ->action(function (Collection $records): void {
                            $cancelledCount = 0;
                            $skippedCount = 0;
                            $cancelAction = app(CancelOrderAction::class);

                            foreach ($records as $record) {
                                if ($record->status === 'completed' || $record->status === 'cancelled') {
                                    $skippedCount++;
                                    continue;
                                }

                                try {
                                    $cancelAction->execute($record);
                                    $cancelledCount++;
                                } catch (\Throwable) {
                                    $skippedCount++;
                                }
                            }

                            $notification = Notification::make()
                                ->title("{$cancelledCount} pesanan berhasil dibatalkan dan stok dikembalikan.");

                            if ($skippedCount > 0) {
                                $notification->body("{$skippedCount} pesanan dilewati karena sudah selesai atau sudah dibatalkan sebelumnya.");
                            }

                            $notification->success()->send();
                        })
                        ->deselectRecordsAfterCompletion(),

                    BulkAction::make('bulk_update_status')
                        ->label('Ubah Status Massal')
                        ->icon('heroicon-o-pencil-square')
                        ->color('primary')
                        ->modalHeading('Ubah Status Pesanan Terpilih Secara Massal')
                        ->modalDescription('Pilih status baru yang akan diterapkan pada seluruh pesanan terpilih.')
                        ->modalSubmitActionLabel('Terapkan Perubahan')
                        ->form([
                            Select::make('status')
                                ->label('Status Baru')
                                ->options([
                                    'pending_payment' => 'Menunggu Pembayaran',
                                    'paid' => 'Sudah Dibayar',
                                    'completed' => 'Selesai',
                                    'cancelled' => 'Dibatalkan (Kembalikan Stok)',
                                ])
                                ->required(),
                            Select::make('payment_method')
                                ->label('Metode Pembayaran (Opsional)')
                                ->placeholder('Jangan ubah metode pembayaran')
                                ->options([
                                    'cash' => 'Tunai',
                                    'qris' => 'QRIS',
                                    'bank_transfer' => 'Transfer Bank',
                                    'manual_transfer' => 'Transfer Manual',
                                ]),
                        ])
                        ->action(function (Collection $records, array $data): void {
                            $newStatus = $data['status'];
                            $newPaymentMethod = $data['payment_method'] ?? null;
                            $updatedCount = 0;
                            $skippedCount = 0;
                            $cancelAction = app(CancelOrderAction::class);
                            $notificationService = app(MemberNotificationService::class);
                            $stamp = AdminShift::stamp(auth()->user());

                            foreach ($records as $record) {
                                if ($newStatus === 'cancelled') {
                                    if ($record->status === 'completed' || $record->status === 'cancelled') {
                                        $skippedCount++;
                                        continue;
                                    }

                                    try {
                                        $cancelAction->execute($record);
                                        if ($newPaymentMethod) {
                                            $record->update(['payment_method' => $newPaymentMethod]);
                                        }
                                        $updatedCount++;
                                    } catch (\Throwable) {
                                        $skippedCount++;
                                    }
                                    continue;
                                }

                                $updateData = ['status' => $newStatus];
                                if ($newPaymentMethod) {
                                    $updateData['payment_method'] = $newPaymentMethod;
                                }
                                if ($newStatus === 'paid' && $record->status !== 'paid' && $record->handled_by === null) {
                                    $updateData = array_merge($updateData, $stamp);
                                }

                                $record->update($updateData);

                                if ($newStatus === 'paid' && $record->member?->user) {
                                    $notificationService->send(
                                        $record->member->user,
                                        'Pesanan sudah dibayar',
                                        'Pesanan produk Anda sudah tercatat lunas di Akhwat Gym.',
                                        'order_paid',
                                        '/orders/'.$record->id,
                                    );
                                }

                                $updatedCount++;
                            }

                            $notification = Notification::make()
                                ->title("{$updatedCount} pesanan berhasil diperbarui.");

                            if ($skippedCount > 0) {
                                $notification->body("{$skippedCount} pesanan dilewati.");
                            }

                            $notification->success()->send();
                        })
                        ->deselectRecordsAfterCompletion(),

                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
