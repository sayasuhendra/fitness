<?php

namespace App\Filament\Resources\PaymentConfirmations\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PaymentConfirmationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Konfirmasi Pembayaran')
                    ->schema([
                        Select::make('status')
                            ->label('Status')
                            ->options([
                                'pending' => 'Menunggu Konfirmasi',
                                'approved' => 'Diterima',
                                'rejected' => 'Ditolak',
                            ])
                            ->required(),
                        TextInput::make('payment_method')
                            ->label('Metode')
                            ->disabled(),
                        TextInput::make('amount')
                            ->label('Nominal')
                            ->prefix('Rp')
                            ->disabled(),
                        FileUpload::make('proof_path')
                            ->label('Bukti Pembayaran')
                            ->disk('public')
                            ->directory('payment-proofs')
                            ->downloadable()
                            ->openable()
                            ->columnSpanFull(),
                        Textarea::make('member_note')
                            ->label('Catatan Member')
                            ->rows(3)
                            ->disabled()
                            ->columnSpanFull(),
                        Textarea::make('admin_note')
                            ->label('Catatan Admin')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->columns(3),
            ]);
    }
}
