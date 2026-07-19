<?php

namespace App\Filament\Resources\PaymentConfirmations\Schemas;

use App\Models\PaymentConfirmation;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Storage;

class PaymentConfirmationInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Detail Konfirmasi Pembayaran')
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
                                'approved' => 'success',
                                'rejected' => 'danger',
                                default => 'warning',
                            }),
                        TextEntry::make('payable_type')
                            ->label('Transaksi')
                            ->formatStateUsing(fn (string $state, PaymentConfirmation $record): string => self::payableLabel($state).' #'.$record->payable_id),
                        TextEntry::make('payment_method')
                            ->label('Metode Pembayaran')
                            ->badge()
                            ->formatStateUsing(fn (string $state): string => self::paymentMethodLabel($state)),
                        TextEntry::make('amount')
                            ->label('Nominal')
                            ->money('IDR'),
                        TextEntry::make('created_at')
                            ->label('Dikirim')
                            ->dateTime(),
                        TextEntry::make('verified_at')
                            ->label('Diverifikasi')
                            ->dateTime()
                            ->placeholder('-'),
                        TextEntry::make('verifier.name')
                            ->label('Diverifikasi Oleh')
                            ->placeholder('-'),
                        TextEntry::make('proof_path')
                            ->label('Bukti Pembayaran')
                            ->formatStateUsing(fn (?string $state): string => $state ? 'Buka Bukti Pembayaran' : 'Belum ada bukti pembayaran')
                            ->url(fn (?string $state): ?string => $state ? Storage::disk('public')->url($state) : null, true)
                            ->color(fn (?string $state): string => $state ? 'primary' : 'gray')
                            ->columnSpanFull(),
                        ImageEntry::make('proof_path')
                            ->label('Preview Bukti Pembayaran')
                            ->disk('public')
                            ->visibility('public')
                            ->imageHeight(320)
                            ->visible(fn (PaymentConfirmation $record): bool => self::isImageProof($record->proof_path))
                            ->columnSpanFull(),
                    ])
                    ->columns(3),
                Section::make('Catatan')
                    ->schema([
                        TextEntry::make('member_note')
                            ->label('Catatan Member')
                            ->placeholder('-')
                            ->columnSpanFull(),
                        TextEntry::make('admin_note')
                            ->label('Catatan Admin')
                            ->placeholder('-')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    private static function payableLabel(string $type): string
    {
        return match (class_basename($type)) {
            'MembershipPurchase' => 'Pembelian Paket',
            'Order' => 'Pesanan Produk',
            'ClassBooking' => 'Booking Kelas',
            'PersonalTrainerSession' => 'Sesi Personal Trainer',
            default => class_basename($type),
        };
    }

    private static function paymentMethodLabel(string $method): string
    {
        return match ($method) {
            'qris' => 'QRIS',
            'bank_transfer' => 'Transfer Bank',
            'cash' => 'Tunai',
            default => $method,
        };
    }

    private static function statusLabel(string $status): string
    {
        return match ($status) {
            'approved' => 'Diterima',
            'rejected' => 'Ditolak',
            default => 'Menunggu Konfirmasi',
        };
    }

    private static function isImageProof(?string $path): bool
    {
        if ($path === null) {
            return false;
        }

        return in_array(strtolower(pathinfo($path, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'webp'], true);
    }
}
