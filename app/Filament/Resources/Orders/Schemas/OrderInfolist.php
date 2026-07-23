<?php

namespace App\Filament\Resources\Orders\Schemas;

use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\RepeatableEntry\TableColumn;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use App\Support\AdminShift;

class OrderInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Detail Pesanan')
                    ->schema([
                        TextEntry::make('member.user.name')
                            ->label('Member'),
                        TextEntry::make('member.member_code')
                            ->label('Kode Member'),
                        TextEntry::make('status')
                            ->label('Status')
                            ->badge()
                            ->formatStateUsing(fn (string $state): string => self::statusLabel($state))
                            ->color(fn (string $state): string => match ($state) {
                                'paid', 'completed' => 'success',
                                'cancelled' => 'danger',
                                default => 'warning',
                            }),
                        TextEntry::make('payment_method')
                            ->label('Metode Pembayaran')
                            ->badge()
                            ->formatStateUsing(fn (string $state): string => self::paymentMethodLabel($state)),
                        TextEntry::make('payment_reference')
                            ->label('Kode Pembayaran')
                            ->placeholder('-'),
                        TextEntry::make('total_price')
                            ->label('Total')
                            ->money('IDR'),
                        TextEntry::make('items_sum_profit_amount')
                            ->label('Profit')
                            ->state(fn ($record): float => (float) $record->items->sum('profit_amount'))
                            ->visible(fn (): bool => auth()->user()?->hasAnyRole(['Owner', 'Super admin']) ?? false)
                            ->money('IDR'),
                        TextEntry::make('handler.name')
                            ->label('Admin')
                            ->placeholder('-'),
                        TextEntry::make('handled_shift')
                            ->label('Shift')
                            ->formatStateUsing(fn (?string $state): string => AdminShift::label($state))
                            ->placeholder('-'),
                        TextEntry::make('delivered_at')
                            ->label('Status Penyerahan')
                            ->formatStateUsing(fn ($state): string => $state ? 'Sudah diserahkan' : 'Belum diserahkan')
                            ->badge()
                            ->color(fn ($state): string => $state ? 'success' : 'warning'),
                        TextEntry::make('deliveredBy.name')
                            ->label('Diserahkan Oleh')
                            ->placeholder('-'),
                        TextEntry::make('created_at')
                            ->label('Dibuat')
                            ->dateTime(),
                        TextEntry::make('updated_at')
                            ->label('Terakhir Diubah')
                            ->dateTime(),
                    ])
                    ->columns(4),
                Section::make('Item Pesanan')
                    ->schema([
                        RepeatableEntry::make('items')
                            ->label('')
                            ->table([
                                TableColumn::make('Produk'),
                                TableColumn::make('Qty'),
                                TableColumn::make('Harga'),
                                TableColumn::make('Subtotal'),
                            ])
                            ->schema([
                                TextEntry::make('product.name')
                                    ->label('Produk'),
                                TextEntry::make('quantity')
                                    ->label('Qty'),
                                TextEntry::make('unit_price')
                                    ->label('Harga')
                                    ->money('IDR'),
                                TextEntry::make('subtotal')
                                    ->label('Subtotal')
                                    ->money('IDR'),
                            ])
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    private static function paymentMethodLabel(string $method): string
    {
        return match ($method) {
            'qris' => 'QRIS',
            'bank_transfer' => 'Transfer Bank',
            'cash' => 'Tunai',
            'manual_transfer' => 'Transfer Manual',
            default => $method,
        };
    }

    private static function statusLabel(string $status): string
    {
        return match ($status) {
            'pending_payment' => 'Menunggu Pembayaran',
            'paid' => 'Sudah Dibayar',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
            default => $status,
        };
    }
}
